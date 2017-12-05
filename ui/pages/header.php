<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="?page=about">Homepage</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="?page=news">News Feed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=about">About us</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Shop
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Security equipment</a>
                    <a class="dropdown-item" href="#">Products</a>
                    <a class="dropdown-item" href="#">Check-boxes</a>
                    <a class="dropdown-item" href="../components/comments.php">Comments</a>

                </div>
            <li class="nav-item">
                <a class="nav-link" href="#">Message board</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact </a>
            </li>
            <?php
            if (logged_in()) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="?page=profile">Welcome <?php echo $_SESSION['user']['username']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=logout">Logout</a>
                </li>
                <?php
            } else {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="?page=register">Register</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="?page=login">Login</a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>