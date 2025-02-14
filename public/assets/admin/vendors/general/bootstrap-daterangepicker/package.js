<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
Package.describe({
  name: 'dangrossman:bootstrap-daterangepicker',
  version: '3.0.3',
  summary: 'Date range picker component',
  git: 'https://github.com/dangrossman/daterangepicker',
  documentation: 'README.md'
});

Package.onUse(function(api) {
  api.versionsFrom('METEOR@0.9.0.1');

  api.use('momentjs:moment@2.22.1', ["client"]);
  api.use('jquery@3.3.1', ["client"]);

  api.addFiles('daterangepicker.js', ["client"]);
  api.addFiles('daterangepicker.css', ["client"]);
});
<<<<<<< HEAD
=======
=======
Package.describe({
  name: 'dangrossman:bootstrap-daterangepicker',
  version: '3.0.3',
  summary: 'Date range picker component',
  git: 'https://github.com/dangrossman/daterangepicker',
  documentation: 'README.md'
});

Package.onUse(function(api) {
  api.versionsFrom('METEOR@0.9.0.1');

  api.use('momentjs:moment@2.22.1', ["client"]);
  api.use('jquery@3.3.1', ["client"]);

  api.addFiles('daterangepicker.js', ["client"]);
  api.addFiles('daterangepicker.css', ["client"]);
});
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
