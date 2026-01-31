<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';

$keyword = isset($_GET['q']) ? sanitize_input($_GET['q']) : '';
$items = [];

if ($keyword) {
    $search = '%' . $keyword . '%';
    $stmt = $mysqli->prepare("SELECT * FROM items WHERE status = 'approved' AND (item_name LIKE ? OR description LIKE ? OR location LIKE ?) ORDER BY created_at DESC");
    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="container">
    <h2 class="text-primary mb-4">Search Lost or Found Items</h2>
    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-8">
            <input type="text" class="form-control" name="q" value="<?php echo $keyword; ?>" placeholder="Search by keyword, location, description" required>
        </div>
        <div class="col-md-4 d-grid d-md-flex gap-2">
            <button class="btn btn-success flex-fill" type="submit">Search</button>
            <button class="btn btn-outline-primary flex-fill" type="button" id="btnAiSuggest" data-query="<?php echo $keyword; ?>">AI Suggest</button>
        </div>
    </form>
    <div id="alertPlaceholder"></div>
    <div id="aiResults" class="mb-4"></div>
    <div class="row g-4">
        <?php if ($keyword && empty($items)): ?>
            <div class="col-12">
                <div class="alert alert-warning">No results found for "<?php echo $keyword; ?>". Try AI suggestions.</div>
            </div>
        <?php endif; ?>
        <?php foreach ($items as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-secondary status-badge text-uppercase"><?php echo $item['item_type']; ?></span>
                        <h5 class="card-title mt-2"><?php echo $item['item_name']; ?></h5>
                        <p class="card-text"><?php echo substr($item['description'], 0, 120); ?>...</p>
                        <p class="text-muted small mb-0"><?php echo $item['location']; ?> · <?php echo format_date($item['created_at']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    document.getElementById('btnAiSuggest').addEventListener('click', async function () {
        const query = document.querySelector('input[name="q"]').value.trim();
        if (!query) {
            alert('Enter a description before requesting AI suggestions.');
            return;
        }
        const aiResults = document.getElementById('aiResults');
        aiResults.innerHTML = '<div class="alert alert-info">Fetching AI suggestions...</div>';
        try {
            const baseUrl = document.body.dataset.baseUrl || '';
            const response = await fetch(`${baseUrl}/api/ai-match.php`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ description: query })
            });
            
            // Handle error responses
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                aiResults.innerHTML = '<div class="alert alert-danger">' +
                    '<strong>Error:</strong> ' + (errorData.message || 'Failed to get AI suggestions') + '<br>' +
                    (errorData.help ? '<small>' + errorData.help + '</small>' : '') +
                    '</div>';
                return;
            }
            
            const data = await response.json();
            if (data.success !== false && data.matches && data.matches.length > 0) {
                let html = '<div class="card shadow-sm"><div class="card-body"><h5>AI Suggested Matches</h5><ul class="list-group list-group-flush">';
                data.matches.forEach(match => {
                    html += `<li class="list-group-item">
                        <strong>${match.item_name}</strong>
                        <div class="small text-muted">${match.location} · Score: ${(match.score * 100).toFixed(1)}%</div>
                        <p class="mb-0">${match.description ? match.description.substring(0, 120) + '...' : 'No description'}</p>
                    </li>`;
                });
                html += '</ul></div></div>';
                aiResults.innerHTML = html;
            } else {
                aiResults.innerHTML = '<div class="alert alert-warning">No AI matches found. Try adjusting your search terms.</div>';
            }
        } catch (error) {
            aiResults.innerHTML = '<div class="alert alert-danger">' +
                '<strong>AI Service Error:</strong><br>' +
                'Failed to contact AI matching service. Make sure the Flask AI service is running.<br>' +
                '<small>Run <code>START_FLASK_AI.bat</code> in the project root to start the service.</small>' +
                '</div>';
        }
    });
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

