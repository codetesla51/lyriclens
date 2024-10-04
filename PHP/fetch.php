<?php

/**Music and track checker based on lyrics description or entering a word used
mostly in so using Spotify api and genuis API
returns response in json easily integrated with fornt end languages
 * 
 * 
**/
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
#the query you can make this Post data form your html 9r cnage here for testing
$query = "i think that i found myself a cheerleader";
#function for getting Spotify getSpotifyAccessToken
function getSpotifyAccessToken()
{
  $clientID = "1f95e0e4f4c243c787a4cb5999e85018";
  $clientSecret = "aeda8568e481482ab6b4e0b577ad4a56";
  #spotify url
  $url = "https://accounts.spotify.com/api/token";
  $headers = [
    "Authorization: Basic " . base64_encode($clientID . ":" . $clientSecret),
  ];
  $data = ["grant_type" => "client_credentials"];
  #initilaize curl
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  #curl response
  $response = curl_exec($ch);

  if ($response === false) {
    die("Error: " . curl_error($ch));
  }
  #return reposnse in json format
  $json = json_decode($response, true);
  curl_close($ch);

  return $json["access_token"];
}
#get Spotify data
function getSpotifySongData($songTitle, $artistName)
{
  $accessToken = getSpotifyAccessToken();
  $query = urlencode($songTitle . " " . $artistName);
  $url =
    "https://api.spotify.com/v1/search?q=" . $query . "&type=track&limit=1";
  $headers = ["Authorization: Bearer " . $accessToken];
  #initilaize curl
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);

  if ($response === false) {
    echo "Error: " . curl_error($ch);
  } else {
    $json = json_decode($response, true);
    #spotify data
    if (!empty($json["tracks"]["items"])) {
      $track = $json["tracks"]["items"][0];
      #preview url
      $previewUrl = $track["preview_url"];
      $releaseDate = $track["album"]["release_date"];
      $albumCover = $track["album"]["images"][0]["url"];
      $genres = getSpotifyArtistGenres($track["artists"][0]["id"]);

      curl_close($ch);

      return [
        "previewUrl" => $previewUrl,
        "releaseDate" => $releaseDate,
        "albumCover" => $albumCover,
        "genres" => $genres,
      ];
    }
  }
  #close curl connection
  curl_close($ch);
  return null;
}
#main function for getting tracks based on description

function getSpotifyArtistGenres($artistId)
{
  $accessToken = getSpotifyAccessToken();
  $url = "https://api.spotify.com/v1/artists/" . $artistId;
  $headers = ["Authorization: Bearer " . $accessToken];

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);

  if ($response === false) {
    echo "Error: " . curl_error($ch);
  } else {
    $json = json_decode($response, true);
    curl_close($ch);
    return !empty($json["genres"])
      ? implode(", ", $json["genres"])
      : "Unknown Genre";
  }

  curl_close($ch);
  return "Unknown Genre";
}
#genuis api url
$url = "https://api.genius.com/search?q=" . urlencode($query);
$accessToken =
  "t7yU4J9jbqJXfNiErw4zcvfZlm139VNvHDlZnZqnccgSK0qjNtpEpHVTDrsHaSKB";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $accessToken]);
$response = curl_exec($ch);

if ($response === false) {
  echo "Error: " . curl_error($ch);
} else {
  $fetch = json_decode($response, true);

  if (!empty($fetch["response"]["hits"])) {
    foreach ($fetch["response"]["hits"] as $hit) {
      $song = $hit["result"];

      // Fetch Spotify data for the song
      $spotifyData = getSpotifySongData(
        $song["title"],
        $song["primary_artist"]["name"]
      );
      if ($spotifyData) {
        echo '
        <div class="res">
            <div class="music-card">
                <div class="music-image">
                    <img src="' .
          htmlspecialchars($spotifyData["albumCover"]) .
          '" alt="Album Cover" />
                </div>
                <div class="music-info">
                    <div class="music-name">' .
          htmlspecialchars($song["title"]) .
          '</div>
                    <div class="artist-name">Artist: ' .
          htmlspecialchars($song["primary_artist"]["name"]) .
          '</div>
                    <div class="genre">Genre: ' .
          htmlspecialchars($spotifyData["genres"]) .
          '</div>
                    <div class="release-date">Release Date: ' .
          htmlspecialchars($spotifyData["releaseDate"]) .
          "</div>";

        if ($spotifyData["previewUrl"]) {
          echo '<audio controls>
                    <source src="' .
            htmlspecialchars($spotifyData["previewUrl"]) .
            '" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>';
        } else {
          echo "<p class='warn'>No audio preview available.</p>";
        }

        echo ' <div class="lyrics-link">
                    <a href="https://genius.com' .
          htmlspecialchars($song["path"]) .
          '" target="_blank">View Full Lyrics</a>
                </div>
                </div>
               
            </div>
        </div>';
      } else {
        echo "<p class='error'>Spotify data not found for this song.</p>";
      }
    }
  } else {
    echo "<p class='error'>No results found for the given description.</p>";
  }
}

curl_close($ch);
/**End curl session. hope the source code was helpful if you need any help
 * reach me through ny website https://uthmandev.vercel.app
 *
 *
 * See you in the next project😀😀
 * don't forget to star ☺️☺️
 *
 */
?>
