import os
from typing import List, Dict

from flask import Flask, request, jsonify
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import spacy

app = Flask(__name__)

MODEL_NAME = os.getenv("SPACY_MODEL", "en_core_web_sm")

try:
    nlp = spacy.load(MODEL_NAME)
except OSError:
    # Fallback to blank English pipeline if model missing
    nlp = spacy.blank("en")


def normalize_text(text: str) -> str:
    doc = nlp(text.lower())
    tokens = [token.lemma_ for token in doc if token.is_alpha and not token.is_stop]
    return " ".join(tokens) if tokens else text.lower()


def build_corpus(query: str, items: List[Dict]) -> List[str]:
    corpus = [normalize_text(query)]
    for item in items:
        text = f"{item.get('item_name', '')} {item.get('description', '')} {item.get('location', '')}"
        corpus.append(normalize_text(text))
    return corpus


@app.post("/match")
def match_items():
    payload = request.get_json(force=True, silent=True) or {}
    query = payload.get("query", "").strip()
    items = payload.get("items", [])

    if not query or not items:
        return jsonify({"matches": [], "message": "Query and items data required"}), 400

    corpus = build_corpus(query, items)
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform(corpus)

    query_vec = tfidf_matrix[0]
    items_vec = tfidf_matrix[1:]

    similarities = cosine_similarity(query_vec, items_vec).flatten()
    ranked = []
    for idx, score in enumerate(similarities):
        ranked.append({
            "item_id": items[idx].get("id"),
            "item_name": items[idx].get("item_name"),
            "description": items[idx].get("description"),
            "location": items[idx].get("location"),
            "item_type": items[idx].get("item_type"),
            "score": float(score),
            "query_label": query[:60]
        })

    ranked.sort(key=lambda r: r["score"], reverse=True)
    top_matches = [match for match in ranked if match["score"] >= 0.35][:5]

    return jsonify({"matches": top_matches, "count": len(top_matches)})


@app.get("/health")
def health():
    return jsonify({"status": "ok"})


if __name__ == "__main__":
    port = int(os.getenv("PORT", 5001))
    app.run(host="0.0.0.0", port=port)

