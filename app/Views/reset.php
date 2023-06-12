
<div class="container form_container">
<h3 class="headings">Reset Password</h3>
<?php if (isset($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>

                    <li><?= $errors ?></li>
           
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email<strong> :</strong></label>
            <input type="email" name="email" class="form-control" id="email">
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>