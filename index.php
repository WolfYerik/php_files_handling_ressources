<?php include('inc/head.php'); ?>

<?php
    function scan($file,$space = ''){
        $path=$file . '/';
        foreach(scandir($file) as $name) {
            if ($name != '.' && $name != '..') {
                if (isset(pathinfo($path.$name)['extension'])){
                    if ( in_array(pathinfo($path.$name)['extension'],['html','txt'])){
                        echo '<a href="/index.php?delete=' . $path . $name . '">X</a>' .
                            "<a href='/index.php?path=$path$name'>$space$name</a><br>";
                    } else {
                        echo '<a href="/index.php?delete=' . $path . $name . '">X</a>' . $space.$name.'<br>';
                    }
                } else {
                    echo '<a href="/index.php?delete=' . $path . $name . '">X</a>' . $space . $name . '<br>';
                }

                if (is_dir($path.$name)){
                    scan($path.$name,$space.'-');
                }
            }
        }
    }
    function delete($file){
        $path=$file . '/';
        if (is_dir($file)){
            foreach(scandir($file) as $name) {
                if ($name != '.' && $name != '..') {
                    if (is_dir($path.$name)){
                        delete($path.$name);
                    } else {
                        unlink($path.$name);
                    }
                }
            }
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    if (isset($_GET['delete'])){
        $path = $_GET['delete'];
        delete($path);
    }
    if (isset($_POST['fileContent'])){
        $path = $_POST['path'];
        $fileContent =$_POST['fileContent'];
        file_put_contents ( $path, $fileContent);
    }

    scan('files');
    ?>
<div>
    <form method="POST" action="index.php">
        <?php
            if (isset($_GET['path'])){
                $path = $_GET['path'];
                $fileContent = file_get_contents($path);
                echo "<input type='hidden' name='path' value='$path'></input>";
                echo "<textarea name='fileContent' cols='100' rows='35'>$fileContent</textarea>";
                echo "<input type='submit' name='submit' value='Valider'/>";
            }
        ?>
    </form>
</div>


<?php include('inc/foot.php'); ?>