

<div class="container form_container">
<h3 class="headings">Change Password</h3>
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
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" aria-describedby="emailHelp" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" id="conf_password">
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>