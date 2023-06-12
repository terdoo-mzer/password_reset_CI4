<!-- <a class="btn logout_btn" href="http://localhost:3000/auth.php?logout">Logout</a> -->
  <a class="btn logout_btn" href="<?php echo base_url('logout'); ?>">Logout</a>
   

    <div class="container profile_container">
    <h3>Hello, <?= $user['name'] ?></h3>
        <p class="title"><span class="bold">User ID: </span><span><?= $user['id'] ?></span></p>
        <p class="title"><span class="bold">Name: </span><span><?= $user['name'] ?></span></p>
        <p class="title"><span class="bold">Email: </span><span><?= $user['email'] ?></span></p>
    </div>
