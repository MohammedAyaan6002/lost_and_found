# Flask AI Matching API

Base URL: `http://127.0.0.1:5001`

## `POST /match`

Returns TF-IDF + cosine similarity scores between the query description and existing items.

### Request Body

```json
{
  "query": "Black backpack with Loyola crest",
  "items": [
    {
      "id": 1,
      "item_name": "Black Backpack",
      "description": "Contains calculus notebook",
      "location": "Science Block",
      "item_type": "lost"
    }
  ]
}
```

### Response

```json
{
  "matches": [
    {
      "item_id": 1,
      "item_name": "Black Backpack",
      "description": "Contains calculus notebook",
      "location": "Science Block",
      "item_type": "lost",
      "score": 0.82,
      "query_label": "Black backpack with Loyola crest"
    }
  ],
  "count": 1
}
```

- Scores range from 0 to 1.
- PHP app records matches with `score >= 0.6` as notifications.

## `GET /health`

Health probe for uptime checks.

