<?php
require("global.php");
$uid = $_GET['id'];
$data=$db->hgetall("user:".$uid);





?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" >
    <title>List</title>
</head>
<script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<body>
<div class="container">
<form action="doedit.php" method="post">
    <input type="hidden" value="<?php echo $data['uid'];?>" name="uid">
    
    
    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label">用户名</label>
        <div class="col-sm-5">
            <input type="text" name="username" class="form-control" value="<?php echo $data['username'];?>">
        </div>
    </div>
    
    
    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label">email</label>
        <div class="col-sm-5">
            <input type="text" name="email" class="form-control" value="<?php echo $data['email'];?>">
        </div>
    </div>
    
    <div class="mb-3 row">
        <label for="inputPassword" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-6">
            <input type="submit" class="btn btn-primary" value="提交">
            <input type="reset" class="btn btn-danger" value="重填">
        </div>
    </div>

</form>
</div>
</body>
</html>
