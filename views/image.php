<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?php if (!Fragment::load(sha1(serialize($image)))): ?>

    <?php echo $image ?>

    <?php Fragment::save() ?>

<?php endif; ?>
