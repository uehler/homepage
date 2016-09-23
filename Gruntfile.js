var srcPath = '_resources/';
var lessSrcPath = srcPath + 'less/';
var lessDestPath = srcPath + 'css/';
var jsSrcPath = srcPath + 'js/';
var jsDestPath = jsSrcPath;
var tplSrcPath = srcPath + 'tpl/';
var tplDestPath = tplSrcPath;
//console.log(tplSrcPath);
module.exports = function (grunt) {
    grunt.initConfig({
        watch: { // watch for file changes
            less: {
                files: [
                    lessSrcPath + '*.less'
                ],
                tasks: [
                    'less',
                    'notify:watch'
                ]
            },
            js: {
                files: [
                    jsSrcPath + 'all_dev.js'
                ],
                tasks: [
                    'jshint',
                    'uglify',
                    'notify:watch'
                ]
            },
            html: {
                files: [
                    tplSrcPath + 'content_dev.html'
                ],
                tasks: [
                    'htmlmin',
                    'notify:watch'
                ]
            }
        },

        jshint: { // check if js has errors
            files: [
                'Gruntfile.js',
                jsSrcPath + 'all_dev.js'
            ]
        },

        uglify: { // minify js
            src: {
                src: [
                    jsSrcPath + 'all_dev.js'
                ],
                dest: jsDestPath + 'all.js'
            }
        },

        less: { // compile less to css
            options: {
                compress: true
            },
            src: {
                src: lessSrcPath + 'all.less',
                dest: lessDestPath + 'all.css'
            }
        },

        htmlmin: {
            dist: {
                options: {
                    removeComments: true,
                    collapseWhitespace: true
                },

                files: {
                    '_resources/tpl/content.html': '_resources/tpl/content_dev.html'
                }
            }
        },

        notify: { // send notification if compiling has finished
            watch: {
                options: {
                    message: 'compiled successful',
                    success: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-notify');


    grunt.registerTask('default', [
        'jshint',
        'uglify',
        'less',
        'htmlmin',
        'notify:watch'
    ]);
};