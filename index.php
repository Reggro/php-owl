<?php

require 'Slim/Slim.php';
require 'vendor/autoload.php';

$app = new \Slim\Slim();

// add article location in configuration
$app->config(array(
    'templates.path' => './templates',
    'article.path' => './articles'   // location of articles
));

// '/post-url' will load post-url.txt file.

$app->get('/submit', function () use ($app) {
    $app->render('form.php');
});

$app->post('/submit', function () use ($app) {
    $body = $app->request()->post();
    $metaData = [
        'title' => $body['title'],
        'date' => $body['date'],
        'slug' => $body['slug'],
        'author' => $body['author']
    ];
    $jsonData = json_encode($metaData);
    //var_dump($body); die;
    $path = $app->config('article.path');
    $handle = fopen($path . '/' . $body['slug'] . '.txt', "w+");
    fwrite($handle, $jsonData . "\n\n" . $body['content']);
    fclose($handle);
    // do mth with $a
    $app->render('form.php', ['message' => 'thank you!']);
});

// assign $this to another variable as it is not supported inside closure
$app->get('/archives(/:yyyy(/:mm(/:dd)))', function () use ($app) {
    $args  = func_get_args();

    $path = $app->config('article.path');
    $dir = new DirectoryIterator($path);
    $articles = [];

    foreach ($dir as $file) {
        if ($file->isFile()) {
            $handle = fopen($path . '/' . $file->getFilename(), 'r');
            $content = stream_get_contents($handle);
            $content = explode("\n\n", $content);
            $rawMeta = array_shift($content);
            $meta = json_decode($rawMeta, true);
            $content = implode("\n\n", $content);
            $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);
        }
    }

    $dateFormat = function ($args,$format) {
        $tempDate = is_array($args) ? implode('-', $args) : $args;
        $date   = new DateTime($tempDate);

        return $date->format($format);
    };

    $archives = [];

    if (count($args)>0) {

        $format = '';
        $date = '';

        switch(count($args)){
            case 1 :    //only year is present
                $format = 'Y';
                $date = $dateFormat($args,$format);
                break;
            case 2 :    //year and month are present
                $format = 'Y-m';
                $date = $dateFormat($args,$format);
                break;
            case 3 :    //year, month and date are present
                $format = 'Y-m-d';
                $date = $dateFormat($args,$format);
                break;
        }
        // filter articles
        foreach ($articles as $article) {
            if ($dateFormat($article['meta']['date'], $format) == $date){
                $archives[] = $article;
            }
        }
    } else {
        $archives = $articles;
    }

    $app->render('archives.php', ['archives' => $archives]);
});


$app->get('/', function () use ($app) {
    $path = $app->config('article.path');
    $dir = new DirectoryIterator($path);
    $articles = [];
    foreach ($dir as $file) {
        if ($file->isFile()) {
            $handle = fopen($path . '/' . $file->getFilename(), 'r');
            $content = stream_get_contents($handle);
            $content = explode("\n\n", $content);
            $rawMeta = array_shift($content);
            $meta = json_decode($rawMeta, true);
            $content = implode("\n\n", $content);
            $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);
        }
    }
    $app->render('index.php', array('articles' => $articles));
});

$app->get('/articles/:article', function ($article) use ($app) {
    $path = $app->config('article.path');
    //open text file and read it
    $handle = fopen($path . '/' . $article . '.txt', 'r');
    $content = stream_get_contents($handle);
    // split the content to get metadata
    $content = explode("\n\n", $content);
    $rawMeta = array_shift($content);
    // metadata is json encoded. so decode it.
    $meta = json_decode($rawMeta,true);
    $content = implode("\n\n", $content);
    $article = array('meta' => $meta , 'content' => $content);
    $app->render('article.php', $article);
});

$app->run();