<div class="verifyemailp secondaryp hdr-border clearfix">
  <div class="container clearfix">
    <div class="row clearfix">
      <div class="col-sm-8 offset-2">
        <div class="forgetmail clearfix">
          <h1>Forget Email Password </h1>
          <p>You can reset password from bellow link: <a href="<?php echo e(route('reset.password', $token)); ?>">Reset Password</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .forgetmail {
    text-align: center;
    padding: 5% 0 0 0;
  }

  .forgetmail h1 {
    font-size: 35px;
    margin: 0;
    padding: 0 0 15px 0;
    font-family: Arial, Helvetica, sans-serif;
    color: #000;
    line-height: normal;
    text-transform: none;
  }

  .forgetmail p {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 21px;
    color: #000;
    line-height: 30px;
    padding: 0 0 15px 0;
    margin: 0;
  }

  .forgetmail p a {
    color: #bf077f;
    text-decoration: none;
  }
</style><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/emails/forgot-password.blade.php ENDPATH**/ ?>