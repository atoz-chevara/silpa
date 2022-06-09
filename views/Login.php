<?php namespace PHPMaker2021\silpa; ?>
<?php

namespace PHPMaker2021\silpa;

// Page object
$Login = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<style>
body {
  background-image: url('images/bg.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;  
  background-size: cover;
}
.ew-login-box {
    margin-top: 182px;
    margin-bottom: 220px;
    background-color:#1E74FF;
    border: 0px solid;
    border-radius: 10px;
}
.login-logo-fix {
    font-size: 6.1rem;
    font-weight: 300;
/*  margin-top: 1px; */
    margin-bottom: 25px;
    text-align: center;
}
.login-card-fix {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
}
.card-body-fix {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 2.25rem;
}
.copyright {
    font-size: 80%;
    color: white;
    margin-top: 20px;
    padding-bottom: 20px;
    text-align: center;
}
</style>
<script>
var flogin;
loadjs.ready("head", function() {
    var $ = jQuery;
    flogin = new ew.Form("flogin");

    // Add fields
    flogin.addFields([
        ["username", ew.Validators.required(ew.language.phrase("UserName")), <?= $Page->Username->IsInvalid ? "true" : "false" ?>],
        ["password", ew.Validators.required(ew.language.phrase("Password")), <?= $Page->Password->IsInvalid ? "true" : "false" ?>]
    ]);

    // Captcha
    <?= Captcha()->getScript("flogin") ?>

    // Set invalid fields
    $(function() {
        flogin.setInvalid();
    });

    // Validate
    flogin.validate = function() {
        if (!this.validateRequired)
            return true; // Ignore validation
        var $ = jQuery,
            fobj = this.getForm();

        // Validate fields
        if (!this.validateFields())
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    flogin.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation
    flogin.validateRequired = <?= JsonEncode(Config("CLIENT_VALIDATE")) ?>;
    loadjs.done("flogin");
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<body>
<form name="flogin" id="flogin" class="ew-form ew-login-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-login-box" style="margin-top: 220px;">
<div class="login-logo-fix"><img src="<?= GetUrl("images/logo_register.png") ?>" alt="" class="brand-image ew-brand-image"></div>
<div class="login-card-fix">
    <div class="card-body-fix">
    <p class="login-box-msg"><?= $Language->phrase("LoginMsg") ?></p>
    <div class="form-group row">
        <input type="text" name="<?= $Page->Username->FieldVar ?>" id="<?= $Page->Username->FieldVar ?>" autocomplete="username" value="<?= HtmlEncode($Page->Username->CurrentValue) ?>" placeholder="<?= HtmlEncode($Language->phrase("Username")) ?>"<?= $Page->Username->editAttributes() ?>>
        <div class="invalid-feedback"><?= $Page->Username->getErrorMessage() ?></div>
    </div>
    <div class="form-group row">
        <div class="input-group"><input type="password" name="<?= $Page->Password->FieldVar ?>" id="<?= $Page->Password->FieldVar ?>" autocomplete="current-password" placeholder="<?= HtmlEncode($Language->phrase("Password")) ?>"<?= $Page->Password->editAttributes() ?>><div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div></div>
        <div class="invalid-feedback"><?= $Page->Password->getErrorMessage() ?></div>
    </div>
    <div class="form-group row">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="<?= $Page->LoginType->FieldVar ?>" id="<?= $Page->LoginType->FieldVar ?>" class="custom-control-input" value="a"<?php if ($Page->LoginType->CurrentValue == "a") { ?> checked<?php } ?>>
            <label class="custom-control-label" for="<?= $Page->LoginType->FieldVar ?>"><?= $Language->phrase("RememberMe") ?></label>
        </div>
    </div>
<?php if (!$Page->IsModal) { ?>
    <button class="btn btn-primary ew-btn" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("Login") ?></button>
<?php } ?>
<?php
// OAuth login
$providers = Config("AUTH_CONFIG.providers");
$cntProviders = 0;
foreach ($providers as $id => $provider) {
    if ($provider["enabled"]) {
        $cntProviders++;
    }
}
if ($cntProviders > 0) {
?>
    <div class="social-auth-links text-center mt-3">
        <p><?= $Language->phrase("LoginOr") ?></p>
<?php
        foreach ($providers as $id => $provider) {
            if ($provider["enabled"]) {
?>
            <a href="<?= CurrentPageUrl(false) ?>?provider=<?= $id ?>" class="btn btn-block btn-<?= strtolower($provider["color"]) ?>"><i class="fab fa-<?= strtolower($id) ?> mr-2"></i><?= $Language->phrase("Login" . $id) ?></a>
<?php
            }
        }
?>
    </div>
<?php
}
?>
<div class="social-auth-links text-center mt-3">
</div>
</div>
</div>
	<div align="center" class="copyright"><?= $Language->projectPhrase("FooterText") ?></div>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
