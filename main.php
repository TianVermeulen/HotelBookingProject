<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Hotel Booking Form</title>
</head>
<body>
<?php
include_once 'connect.php';

$sql = "CREATE TABLE booking (
    id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    surname VARCHAR(64) NOT NULL,
    hotel VARCHAR(64) NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL)";

if(mysqli_query($conn, $sql)){
    echo '<p>Table film created succesffully. </p>';
}else{
    echo '<p> Error creating table: ' . mysqli_error($conn) . "</p>";
}

?>
<div class="flex-container">
<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<label for="name">First Name:</label>
<input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" required>
<label for="surname">Surname:</label>
<input type="text" name="surname" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : '' ?>" required>
<label for="start">Start Date:</label>
<input type="date" id="start" name="startDate" min="2019-01-01" max="2020-01-01" value="<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : '' ?>">
<label for="end">End Date:</label>
<input type="date" id="end" name="endDate" min="2019-01-01" max="2020-01-01" value="<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : '' ?>">
<label for="hotels">Hotels:</label>
<select name="hotels">
    <option value="st Paul" <?php if (isset($_POST['hotels']) && $_POST['hotels']=="st Paul") echo "selected"; ?>>st Paul</option>
    <option value="Little" <?php if (isset($_POST['hotels']) && $_POST['hotels']=="Little") echo "selected"; ?>>Little</option>
    <option value="Red" <?php if (isset($_POST['hotels']) && $_POST['hotels']=="Red") echo "selected"; ?>>Red</option>
<br>
<label for="display">View your booking: </label>
<input type="submit" name="display">

<br>


<?php
if($_POST){
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['surname'] = $_POST['surname'];
    $_SESSION['startDate'] = $_POST['startDate'];
    $_SESSION['endDate'] = $_POST['endDate'];
    $_SESSION['hotels'] = $_POST['hotels'];
    
    


$date1 = new DateTime($_SESSION['startDate']);
$date2 = new DateTime($_SESSION['endDate']);
$days = date_diff($date1, $date2);



switch($_SESSION['hotels']){
    case "st Paul":
    $rate = $days->format('%d') * 100;
    break;
    case "Little":
    $rate = $days->format('%d') * 200;
    break;
    case "Red":
    $rate = $days->format('%d') * 300;
    break;

   

}
if(isset($_POST['display'])){
    $display = $_POST['display'];
    if($display){
echo "<p>" . "Name: " . $_SESSION['name'] . "<br>" .
"Surname: " . $_SESSION['surname'] . "<br>" . 
"Start Date: " . $_SESSION['startDate'] . "<br>" . 
"End Date: " . $_SESSION['endDate'] . "<br>" . 
"Hotel: " . $_SESSION['hotels'] . "<br>" . 
"Length of Stay: " . $days->format('%d days') . "<br>" . 
"Price: " ."R". $rate . "</p>" . "<br>";
}
}
}
?>


<input type="submit" name="book">
</div>
</form>
<?php 
if(isset($_POST['book'])){
    $nameID = $_POST['name'];
    $surnameID = $_POST['surname'];
    $duplicate = mysqli_query($conn,"select * from booking where name = '$nameID' and surname = '$surnameID'");
    if (mysqli_num_rows($duplicate)>0){
        echo "<p> Sorry this user already exists </p>";
    }else{

$book = $_POST['book'];
if($book){
echo "<p> Booking successful! </p>";

    $stmt = $conn->prepare("INSERT INTO booking (name, surname, hotel, startDate, endDate)
    VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $surname, $hotels, $startDate, $endDate);
    
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $hotels = $_SESSION['hotels'];
    $startDate = $_SESSION['startDate'];
    $endDate = $_SESSION['endDate'];
    $stmt->execute();  
}
    
}
    }
 

?>
    
</body>
</html>