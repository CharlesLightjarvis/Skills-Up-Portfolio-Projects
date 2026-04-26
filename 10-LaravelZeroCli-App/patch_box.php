<?php

/**
 * Patches Box.php inside the Box PHAR to fix a Windows CWD locking bug.
 *
 * Problem: endBuffering() calls chdir($tmp) then FS::remove($tmp) in a finally block.
 * On Windows, rmdir() fails when the directory is the current working directory.
 * Fix: call chdir($cwd) first to release the CWD lock before deleting the temp dir.
 */
$boxPath = __DIR__.'/vendor/laravel-zero/framework/bin/box';
$patchedPath = __DIR__.'/vendor/laravel-zero/framework/bin/box_patched.phar';
$backupPath = __DIR__.'/vendor/laravel-zero/framework/bin/box_original';

if (! file_exists($boxPath)) {
    echo "ERROR: Box binary not found at $boxPath\n";
    exit(1);
}

// Read current content to check if already patched
$alias = 'box-auto-generated-alias-7b8f6097454f.phar';
Phar::loadPhar($boxPath, $alias);
$currentContent = file_get_contents('phar://'.$alias.'/src/Box.php');

$old = "finally {\n            FS::remove(\$tmp);\n            chdir(\$cwd);\n        }";
$new = "finally {\n            chdir(\$cwd);\n            FS::remove(\$tmp);\n        }";

if (strpos($currentContent, $new) !== false) {
    echo "Box.php is already patched. Nothing to do.\n";
    exit(0);
}

if (strpos($currentContent, $old) === false) {
    echo "ERROR: Expected pattern not found in Box.php. The Box version may have changed.\n";
    echo "Looking for:\n$old\n";
    exit(1);
}

$fixedContent = str_replace($old, $new, $currentContent);

// Back up original if not already done
if (! file_exists($backupPath)) {
    copy($boxPath, $backupPath);
    echo "Backed up original to: $backupPath\n";
}

// Copy to a .phar file so Phar class can open it
copy($boxPath, $patchedPath);

$phar = new Phar($patchedPath);
$phar->startBuffering();
$phar['src/Box.php'] = $fixedContent;
$phar->stopBuffering();
unset($phar);

// Replace original with patched version
copy($patchedPath, $boxPath);
unlink($patchedPath);

echo "Box.php patched successfully.\n";
echo "endBuffering() now calls chdir(\$cwd) before FS::remove(\$tmp).\n";
