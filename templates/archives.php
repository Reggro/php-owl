<html>
    <head>
        <title>Archives</title>
    </head>
    <body>
        <h1>Archives</h1>
        <?php foreach ($archives as $archive): ?>
        <h1><?php echo $archive['meta']['title']; ?></h1>
            <?php echo substr(strip_tags($archive['content']), 0,200)
                . '... <a href="/' . $archive['meta']['slug']
                . '">Read more >> </a>'; ?>
        <?php endforeach; ?>
    </body>
</html>