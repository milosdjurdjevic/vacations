<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Vacations</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <? if ($_SESSION['user']['roleId'] == 1): ?>
                    <li><a href="employees.php">Manage employees</a></li>
                    <li><a href="vacation_requests.php">Vacation requests</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <? else: ?>
                    <li class=""><a href="overview.php">Home</a></li>
                    <li><a href="request_vacation.php">Request a vacation</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <? endif; ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>