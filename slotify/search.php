<?php
    include('includes/includedFiles.php');

    if(isset($_GET['term'])) {
        $term = urldecode($_GET['term']);
    } else {
        $term = "";
    }
?>

<div class="searchContainer">

    <h4>Search for an artist, album, or song</h4>
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Enter your search criteria here..." spellcheck="false" / >
</div>

<script>
    
    var selector = $(".searchInput");
    var carat = selector.val().length;
    selector.focus();
    selector[0].setSelectionRange(carat, carat);

    $(function() {
        var timer;
        $(".searchInput").keyup(function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                var val = $(".searchInput").val();
                openPage("search.php?term=" + val);
            }, 2000);
        });
    });
</script>

<div class="tracklistContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="tracklist">
        <?php
            $songsQuery = mysqli_query($con, "SELECT id FROM songs where title LIKE '$term%' LIMIT 10");

            if(mysqli_num_rows($songsQuery) == 0) {
                echo "<span class='noResults'>No sounds found matching ". $term . "</span>";
            }

            $songIdArray = [];

            $i = 1;
            foreach($songsQuery as $songs) {
                if($i > 15) {
                    break;
                }
                
                array_push($songIdArray, $songs['id']);
                $albumSong = new Song($con, $songs['id']);
                $albumArtist = $albumSong->getArtist();
                echo "
                <li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(". $albumSong->getId() .", tempPlaylist, true)' />
                        <span class='trackNumber'>$i</span>
                    </div>

                    <div class='trackInfo'>
                        <span class='trackName'>" . $albumSong->getTitle() . "</span>
                        <span class='artistName'>" . $albumArtist->getName() . " </span>
                    </div>

                    <div class='trackOptions'>
                        <img class='optionsButton' src='assets/images/icons/more.png' />
                    </div>

                    <div class='trackDuration'>
                        <span class='duration'>" . $albumSong->getDuration() . "</span>
                    </div>

                </li>";
                $i++;
            }
    ?>
        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>

<div class="artistsContainer borderBottom">

<h2>ARTISTS</h2>

<?php
$artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

if(mysqli_num_rows($artistsQuery) == 0) {
    echo "<span class='noResults'>No artists found matching " . $term . "</span>";
}

foreach($artistsQuery as $artistId) {
    $artistFound = new Artist($con, $artistId['id']);

    echo "<div class='searchResultRow'>
            <div class='artistName'>

                <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() ."\")'>
                "
                . $artistFound->getName() .
                "
                </span>

            </div>

        </div>";

}


?>

</div>