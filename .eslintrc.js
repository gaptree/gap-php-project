module.exports = {
    "env": {
        "browser": true,
        "commonjs": true,
        "es6": true,
        "jest": true
    },
    "extends": ["eslint:recommended"],
    "parserOptions": {
        "sourceType": "module"
    },
    "rules": {
        "indent": ["error", 4],
        "linebreak-style": ["error", "unix"],
        //"quotes": ["error", "double"],
        "semi": ["error", "always"],
        "no-else-return": "error"
    }
};
