<?php
    include 'dbh.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="mystyle.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
        $(document).ready(function(){
             $("#btn").click(function(){
                $("#test").load("data.php");
            });
        });
        </script>

        <a style='text-decoration:none;' href='dashboard3p0.php'>
            <div class="brand">Sonoco Reels & Plugs</div>
        </a>
        <div class="brand timeStamp"></div>
        <div class='dashboard'>
            <table>
                
                    <td><a style='text-decoration:none;' href='DTEvents.php?c=$i' ></a></td>
                    <script src="actions.js"></script>
            </table>
        </div>
        <div id="comments"> 
            <?php
            $sql ="SELECT * FROM dashinfo limit 2";
            $result = mysqli_query($sql);
            if(mysqli_num_rows() > 0){
                while ($row = mysqli_fetch_assoc()){
                    echo"<p>";
                    echo $row['orderIndex'] ;
                    echo "<br>";
                    echo $row['lineLabels'] ;
                    echo"</p>";
                }

            }
            else{
                echo "There are no comments!";
            }
            ?>
        </div>
        
    </head>
</html>