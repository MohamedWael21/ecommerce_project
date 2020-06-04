<?php
include '/var/webroot/myproject2/include/classes.php';
$p = new page("search");
$p->header();
$p->navbar();
?>
<div class="container search">
    <div class="searchbar">
        <form action="search.php" method="POST">
            <div class="form-group">
                <input type="text" id="searchBar" class="form-control"  name="search">
                <input type="submit" class="btn btn-primary">
            </div>
        </form>
        <div class="result" id="liveresult">
            
        </div>
    </div>
</div>

<?php
$p->footer();
