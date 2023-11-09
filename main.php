<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TASK-LIST</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link href="taskhandler.php">
    </head>
    <body>
        
        <header>
            <b>### Task list ###</b>
        </header>
        
        <section class="box">
            <article class="art1">
                <?php echo getTasks(); ?>      
            </article>
            
            <article class="art2">
                <h2>Add task</h2>    
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <label class="label1" for="author">Author</label>
                    <input id="author" type="text" name="author" placeholder="Author..." required>
                    
                    <label for="task">Task</label>
                    <input class="task" id="task" type="text" name="task" placeholder="Write your task..." required>
                    
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority">
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>

                    <button class="btn1" type="submit" name="submit">Submit</button>
                </form>
            </article>
        </section>

    </body>

</html>        
        
<?php

    // NECCESARY OBJECTS-------------------------------------------------------------

    function addTask(){

        if (isset($_POST['submit'])) {

            // Validate the correct use of the button
            $author       = htmlspecialchars($_POST["author"]);
            $name         = htmlspecialchars($_POST["task"]);
            $priority     = htmlspecialchars($_POST["priority"]);
            
            // Error handlers    
            if (empty($author) || empty($name)) {
                return "<p class='form-error'>Fill in all fields!</p>";
            }

            $task = array(
                'author'    => $author,
                'name'      => $name,
                'priority'  => $priority
            );                
            
            saveTask($task);

            // Upload de page
            header("Location: main.php");
        }
            
    }

    function showTask($task){

        echo "<tr>";
        echo "<p class = 'final-task1'>" . $task['name'] . "</p>";
        echo "<form action = '' method = 'POST'>";
        echo '  <input class="name" id="name" type="text" name="name" value = "' . $task['name'] . '" hidden> ';
        echo '  <p class="final-task2"> By: ' . $task['author'] . '<em class="'. $task['priority'] .'"> '. $task['priority'] .'</em>';
        echo '  <button class="btn2" type="submit" name="delete">Delete</button></p>';
        echo '</form>';
        echo '</tr>';
    }

    function eliminateTask($name){

        if (isset($_POST['delete'])) {

            // Error handlers    
            if (empty($name)) {
                return "<p class='form-error'>Delete failed!</p>";
            }         
            
            deleteTask($name);

            // Upload de page
            header("Location: main.php");
        }
    }
        
    // FUNCTIONS OF DATABASE-------------------------------------------------

    // Create connection
    function getConnection() {

        $conn = mysqli_connect(
            "mysql", 
            "root", 
            "root",
            "example"
        );
        
        return $conn;
    }
    
    function saveTask($task) {
    
        $conn = getConnection();
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        if (empty($task) || !is_array($task)) {
            die ('No se ha encontrado la tarjeta');
        }

        mysqli_query(
            $conn, 
            "INSERT INTO Tasks (author, name, priority) VALUES ('" . $task['author'] . "', '" . $task['name'] . "', '" . $task['priority'] . "')"
        );

        mysqli_close($conn);
    }

    function getTasks() {
    
        $conn = getConnection();

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $resultSQL = mysqli_query(
            $conn, 
            "SELECT * FROM Tasks"
        );

        if (!$resultSQL) {
            throw ('Existe un error en la base de datos');
        }

        while ($row = mysqli_fetch_array($resultSQL)){
            showTask($row);
        }
    }

    function deleteTask($name) {
    
        $conn = getConnection();
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        if (empty($name)) {
            die ('No se ha encontrado la tarjeta');
        }

        mysqli_query(
            $conn, 
            "Delete FROM Tasks WHERE `name` = '" . $name . "';"
        );

        mysqli_close($conn);
    }

    if(isset($_POST['submit'])){
        echo addTask();
    }

    if(isset($_POST['delete'])){
        echo eliminateTask($_POST['name']);
    }
?>
