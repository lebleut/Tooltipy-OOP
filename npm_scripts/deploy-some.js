var FtpDeploy = require('ftp-deploy');
var ftpDeploy = new FtpDeploy();
const gitFiles = require('git-files')
var dotenv = require('dotenv').config()

// Get all modified files from git and add them to files to upload
upload_files = gitFiles.all()

console.log( upload_files )

var config = {
    host: process.env.SITE_HOST, // Create a .env file in which you store the environment vars
    port: 21,
    user: process.env.SITE_USER,
    password: process.env.SITE_PASS,

    localRoot: __dirname+'/..',
    remoteRoot: process.env.SITE_REMOTE_ROOT,
    //include: [ '*', 'includes/**', 'css/**', 'js/**' ],      // this would upload everything except dot files
    include: upload_files,
    exclude: [
        'package.json',
        'node_modules/**/*',
        'npm_scripts/**/*',
        '.git/**/*',
        'dist/**/*.map',
    ],     // e.g. exclude sourcemaps
    deleteRoot: true                // delete existing files at destination before uploading
}

ftpDeploy.deploy(config)
    .then(res => console.log('**** UPLOAD DONE ****'))
    .catch(err => console.log(err))

ftpDeploy.on('uploading', function(data) {

});

ftpDeploy.on('uploaded', function(data) {
    console.log(data.filename+' ... Uploaded')
});

ftpDeploy.on('upload-error', function (data) {
    console.log('****** upload - ERROR ******');
    console.log(data.err); // data will also include filename, relativePath, and other goodies
    console.log('------------------------------');
}); 