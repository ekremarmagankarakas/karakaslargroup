<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

require_once 'classes/dbh.classes.php';
require_once 'classes/requirements.classes.php';
require_once 'classes/users.classes.php';

$userObj = new User();
$users = $userObj->getAllUsers();

$requirement = new Requirement();
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;
$usernameFilter = isset($_GET['username']) ? $_GET['username'] : null;
$statusFilter = isset($_GET['status']) ? $_GET['status'] : null;
$dateRangeFilter = isset($_GET['dateRange']) ? $_GET['dateRange'] : null;

$filters = [];
if ($searchQuery !== null) {
    $filters['search'] = $searchQuery;
}
if ($usernameFilter !== null) {
    $filters['username'] = $usernameFilter;
}
if ($statusFilter !== null) {
    $filters['status'] = $statusFilter;
}
if ($dateRangeFilter !== null) {
    $filters['dateRange'] = $dateRangeFilter;
}

$filterQueryString = http_build_query(array(
    'search' => $filters['search'] ?? '',
    'username' => $filters['username'] ?? '',
    'status' => $filters['status'] ?? '',
    'dateRange' => $filters['dateRange'] ?? ''
));

if ($_SESSION["usertype"] == "manager" OR $_SESSION["usertype"] == "accountant") {
    $entries = $requirement->getRequirementsPaginated(null, $currentPage, $limit, $filters);
    $totalEntries = $requirement->getTotalEntries(null, $filters);
    $totalAccepted = $requirement->getTotalEntriesByStatus('Accepted', null, $filters);
    $totalDeclined = $requirement->getTotalEntriesByStatus('Declined', null, $filters);
    $totalPending = $requirement->getTotalEntriesByStatus('Pending', null, $filters);
    $totalPrice = $requirement->getTotalPrice(null, $filters); 
    $totalPriceAccepted = $requirement->getTotalPriceByStatus('Accepted', null, $filters);
    $totalPriceDeclined = $requirement->getTotalPriceByStatus('Declined', null, $filters);
    $totalPricePending = $requirement->getTotalPriceByStatus('Pending', null, $filters);
} else {
    $entries = $requirement->getEmployeeRequirementsPaginated($_SESSION['userid'], $currentPage, $limit, $filters);
    $totalEntries = $requirement->getTotalEntries($_SESSION['userid'], $filters);
    $totalAccepted = $requirement->getTotalEntriesByStatus('Accepted', $_SESSION['userid'], $filters);
    $totalDeclined = $requirement->getTotalEntriesByStatus('Declined', $_SESSION['userid'], $filters);
    $totalPending = $requirement->getTotalEntriesByStatus('Pending', $_SESSION['userid'], $filters);
    $totalPrice = $requirement->getTotalPrice($_SESSION['userid'], $filters);
    $totalPriceAccepted = $requirement->getTotalPriceByStatus('Accepted', $_SESSION['userid'], $filters);
    $totalPriceDeclined = $requirement->getTotalPriceByStatus('Declined', $_SESSION['userid'], $filters);
    $totalPricePending = $requirement->getTotalPriceByStatus('Pending', $_SESSION['userid'], $filters);
}

$totalPages = ceil($totalEntries / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="dashboard.php" class="navbar-logo"> <img src="img/logo.png" alt="KarakaslarGroup Logo"></a>
            <div class="navbar-right">
                <?php if ($_SESSION["usertype"] == "manager"): ?>
                    <div class="dropdown">
                        <button class="dropbtn">Manager Menu</button>
                        <div class="dropdown-content">
                            <a href="#">User Management</a>
                            <a href="#">Reports</a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($_SESSION["usertype"] == "employee"): ?>
                <button class="dropbtn" id="toggleFormButton">New Entry</button>
                <?php endif; ?>
                <a href="profile.php">Profile</a>
                <a href="includes/logout.inc.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- New Entry Form -->
    <?php if ($_SESSION["usertype"] == "employee"): ?>
        <form action="includes/submitRequirement.inc.php" method="post" id="newEntryForm" style="display: none;">
            <input type="text" name="itemName" placeholder="Item Name" required>
            <input type="number" step="0.01" name="price" placeholder="Estimated Price" required>
            <textarea name="explanation" placeholder="Explanation" required></textarea>
            <button type="submit" name="submit">Submit Requirement</button>
        </form>
    <?php endif; ?>

    <!-- Filter -->
    <div class="filter-container">
        <form action="dashboard.php" method="get" class="filter-form">
            <div class="filter-field">
            <?php if ($_SESSION["usertype"] == "manager" OR $_SESSION["usertype"] == "accountant"): ?>
                <label for="username">Username:</label>
                <select id="username" name="username">
                    <option value="">Select a user</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['users_id']) ?>"><?= htmlspecialchars($user['users_id']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            </div>
            <div class="filter-field">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Declined">Declined</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="filter-field">
                <label for="dateRange">Date Range:</label>
                <select id="dateRange" name="dateRange">
                    <option value="">Select Range</option>
                    <option value="week">Last Week</option>
                    <option value="month">Last Month</option>
                    <option value="year">Last Year</option>
                </select>
            </div>
            <button type="submit" class="filter-button">Filter</button>
        </form>
    </div>


    <!-- Search -->
    <div class="search-container">
        <form action="dashboard.php" method="get" class="search-form">
            <input type="text" name="search" placeholder="Search requirements..." value="<?= htmlspecialchars($searchQuery); ?>">
            <button type="submit">
                <span class="search-icon">&#128269;</span>
                Search
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="statistics">
        <h3>Statistics</h3>
            <div class="stats">
            <div class="stat-item">
                <p><strong>Total Requests:</strong> <?= htmlspecialchars($totalEntries) ?></p>
                <p><strong>Total Pending Requests:</strong> <?= htmlspecialchars($totalPending) ?></p>
                <p><strong>Total Accepted Requests:</strong> <?= htmlspecialchars($totalAccepted) ?></p>
                <p><strong>Total Declined Requests:</strong> <?= htmlspecialchars($totalDeclined) ?></p>
            </div>
            <div class="stat-item">
                <p><strong>Total Price of Requests:</strong> <?= htmlspecialchars(number_format($totalPrice, 2)) ?></p>
                <p><strong>Total Price of Pending Entries:</strong> <?= htmlspecialchars($totalPricePending) ?></p>
                <p><strong>Total Price of Accepted Entries:</strong> <?= htmlspecialchars($totalPriceAccepted) ?></p>
                <p><strong>Total Price of Declined Entries:</strong> <?= htmlspecialchars($totalPriceDeclined) ?></p>
            </div>
        </div>
    </div>

     <!-- Entries -->
    <div class="entries">
        <?php if (!empty($entries)): ?>
            <?php foreach ($entries as $entry): ?>
                <div class="entry <?php echo ($entry['status'] == 'Accepted') ? 'entry-accepted' : (($entry['status'] == 'Declined') ? 'entry-declined' : ''); ?>">
                    <?php if ($_SESSION["usertype"] == "manager"): ?>
                        <p><strong>User:</strong> <?= htmlspecialchars($entry['users_uid']); ?></p>
                    <?php endif; ?>
                    <p><strong>Item Name:</strong> <?= htmlspecialchars($entry['item_name']); ?></p>
                    <p><strong>Price:</strong> <?= htmlspecialchars($entry['price']); ?></p>
                    <p><strong>Explanation:</strong> <?= htmlspecialchars($entry['explanation']); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($entry['status']); ?></p>
                    <p><strong>Submitted On:</strong> <?= htmlspecialchars($entry['creation_date']); ?></p>
                    <?php if ($_SESSION["usertype"] == "manager"): ?>
                        <a href="includes/handleEntry.inc.php?action=accept&id=<?= $entry['requirement_id']; ?>" class="btn-accept">Accept</a>
                        <a href="includes/handleEntry.inc.php?action=decline&id=<?= $entry['requirement_id']; ?>" class="btn-decline">Decline</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No entries found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination Controls -->
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="dashboard.php?page=<?= $currentPage - 1; ?>&<?= $filterQueryString ?>">&laquo; Previous</a>
        <?php endif; ?>
        <?php if ($currentPage < $totalPages): ?>
            <a href="dashboard.php?page=<?= $currentPage + 1; ?>&<?= $filterQueryString ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById("toggleFormButton").addEventListener("click", function() {
            var form = document.getElementById("newEntryForm");
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>
</html>
