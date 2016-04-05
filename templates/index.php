<?php
//form -> date rendername
foreach($articles as $article){
    echo "<h1> ". $article['meta']['title'] ." </h1> ";
    echo substr(strip_tags($article['content']), 0,200)
    . '... <a href="http://localhost:8888/test/php_owl/' . $article['meta']['slug']
                . '">Read more >> </a>';
}