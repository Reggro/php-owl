<html>
    <head>Form</head>
    <body>
    <?php if (isset($message)): ?>
    <p><?php echo $message; ?></p>
    <?php endif;?>
        <form  name="archive" action="http://localhost:8888/test/php_owl/submit" method="post">
            <label for="date">Date</label>
            <input type="text" id="date" name="date" placeholder="yyyy/mm/dd">
            <label for="title">Tite</label>
            <input type="text" id="title" name="title">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug">
            <label for="author">Author</label>
            <input type="text" id="author" name="author">
            <label for="title">Tite</label>
            <textarea name="content" id="content" rows="10" cols="100"></textarea>
            <input type="submit">
        </form>
    </body>
</html>