document.addEventListener("DOMContentLoaded", () => {
    const baseUrl = document.body.dataset.baseUrl || "";
    const alertPlaceholder = document.querySelector("#alertPlaceholder");
    document.querySelectorAll("form.needs-validation").forEach(form => {
        form.addEventListener("submit", event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        });
    });

    const bindSubmission = (formId, type) => {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (!form.checkValidity()) {
                event.stopPropagation();
                return;
            }
            const formData = new FormData(form);
            formData.append("item_type", type);
            try {
                const response = await fetch(`${baseUrl}/api/submit-item.php`, {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                alert(data.message);
                if (data.success) {
                    form.reset();
                    form.classList.remove("was-validated");
                }
            } catch (error) {
                alert("Submission failed. Try again later.");
            }
        });
    };

    bindSubmission("lostForm", "lost");
    bindSubmission("foundForm", "found");

    document.querySelectorAll("[data-demo-alert]").forEach(btn => {
        btn.addEventListener("click", () => {
            if (alertPlaceholder) {
                alertPlaceholder.innerHTML = `
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Demo notification sent. Configure email/SMS hooks in production.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }
        });
    });
});

