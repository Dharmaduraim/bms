<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1" />
      <title>Admin Login</title>
      <link
         href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
         rel="stylesheet"
      />
      <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet" />
   </head>
   <body class="login-page">
      <main class="login-card">
         <h2>Invoice & Budget</h2>
         <p class="text-muted">Sign in to your admin account</p>
         <?php if(!empty($error)): ?>
         <div class="alert alert-danger"><?= $error ?></div>
         <?php endif; ?>
         <form method="post" action="<?= base_url('login') ?>">
            <input
               type="hidden"
               name="<?= $this->security->get_csrf_token_name() ?>"
               value="<?= $this->security->get_csrf_hash() ?>"
            />
            <div class="mb-3">
               <label class="form-label">Email</label
               ><input
                  class="form-control"
                  type="email"
                  name="email"
                  value="<?= set_value('email') ?>"
               />
            </div>
            <div class="mb-3">
               <label class="form-label">Password</label
               ><input class="form-control" type="password" name="password" />
            </div>
            <button class="btn btn-primary w-100">Login</button>
         </form>
         <small class="d-block mt-3 text-muted"
            >Demo: admin@example.com / password</small
         >
      </main>
   </body>
</html>
