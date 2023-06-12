<nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('signup') ?>">Signup</a></li>
        <li class="breadcrumb-item active" aria-current="page">Login</li>
    </ol>
</nav>
<div class="container form_container">
<h3 class="headings">Login</h3>
<?php if (isset($errors) && count($errors) > 0) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
        <div id="emailHelp" class="form-text">Forgot your password?<a href="/forgot-password">Reset here</a></div>
    </form>
</div>