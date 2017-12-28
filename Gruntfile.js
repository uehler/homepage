module.exports = function (grunt) {

    const jsFile = 'js/all.js';
    const lessFile = 'less/all.less';
    const cssFile = 'css/all.css';
    const indexFile = 'index.html';

    grunt.initConfig({
        htmlmin: {
            dist: {
                options: {
                    removeComments: true,
                    collapseWhitespace: true
                },

                files: {
                    'index.html': indexFile
                }
            }
        },

        inline: {
            dist: {
                src: indexFile,
                dest: indexFile
            }
        },

        jshint: {
            files: [
                'Gruntfile.js',
                jsFile
            ],
            options: {
                esnext: true
            }
        },

        less: { // compile less to css
            options: {
                compress: true
            },
            src: {
                src: lessFile,
                dest: cssFile
            }
        },

        notify: {
            watch: {
                options: {
                    message: 'compiled successful',
                    success: true
                }
            }
        },

        uglify: { // minify js
            src: {
                src: [
                    jsFile
                ],
                dest: jsFile
            }
        },

        uncss: {
            dist: {
                files: {
                    'css/all.css': [indexFile]
                }
            }
        },

        watch: {
            js: {
                files: [
                    jsFile
                ],
                tasks: [
                    'jshint',
                    'notify:watch'
                ]
            },

            less: {
                files: [
                    'less/*.less'
                ],
                tasks: [
                    'less',
                    'uncss',
                    'notify:watch'
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-inline');
    grunt.loadNpmTasks('grunt-notify');
    grunt.loadNpmTasks('grunt-uncss');


    grunt.registerTask('default', [
        'jshint',
        'less',
        'uncss',
        'notify:watch'
    ]);

    grunt.registerTask('deploy', [
        'uglify',
        'less',
        'uncss',
        'inline',
        'htmlmin',
        'notify:watch'
    ]);
};