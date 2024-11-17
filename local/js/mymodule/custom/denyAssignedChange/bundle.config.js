const commonjs = require('rollup-plugin-commonjs');

module.exports = {
    input: 'src/script.js',
    output: 'dist/script.bundle.js',
    namespace: 'BX.MyModule.Custom',
    sourceMaps: false,
    minification: true,
    plugins: {
        resolve: true,
        custom: [
            commonjs(),
        ],
    }
};
