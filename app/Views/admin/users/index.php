<?php

        require_once(BASE_PATH . '/app/Views/admin/layouts/head-tag.php');


?>
     <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5"><i class="fas fa-newspaper"></i> Users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a role="button" href="<?= url('admin/users/create') ?>" class="btn btn-sm btn-success">create</a>
        </div>
    </div>
    <section class="table-responsive">
        <table class="table table-striped table-sm">
            <caption>List of users</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>username</th>
                    <th>email</th>
                    <th>permission</th>
                    <th>created at</th>
                    <th>setting</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $key => $user) { ?>
                <tr>
                    <td><?= $key += 1 ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <?php if($user['permission'] == 'admin') { ?>
                            <span class="badge bg-success">Admin</span>
                        <?php } else { ?>
                            <span class="badge bg-primary">User</span>
                        <?php } ?>
                    </td>
                    <td><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                    <td>
                        <?php if($user['permission'] == 'user') { ?>   
                            <a role="button" class="btn btn-sm btn-success text-white" href="<?= url('admin/users/' . $user['id'] . '/promote') ?>" onclick="return confirm('Make this user an admin?')">Make Admin</a>
                        <?php } else { ?>
                            <a role="button" class="btn btn-sm btn-warning text-white" href="<?= url('admin/users/' . $user['id'] . '/demote') ?>" onclick="return confirm('Remove admin privileges?')">Remove Admin</a>
                        <?php } ?>
             

                <a role="button" class="btn btn-sm btn-primary text-white" href="<?= url('admin/users/' . $user['id'] . '/edit') ?>">edit</a>
                <a role="button" class="btn btn-sm btn-danger text-white" href="<?= url('admin/users/' . $user['id']) ?>" onclick="return confirm('Are you sure?')">delete</a>
                </td>
                </tr>

                <?php } ?>
                </tbody>
                </table>
                </section>


    <?php

        require_once(BASE_PATH . '/app/Views/admin/layouts/footer.php');


?>
