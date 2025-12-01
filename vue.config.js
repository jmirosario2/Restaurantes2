// babel.config.js
module.exports = {
  presets: [
    '@vue/cli-plugin-babel/preset', // o el preset que uses de Vue
    // Asegúrate de que el preset-env esté ahí para manejar ES moderna
    ['@babel/preset-env', { modules: false }]
  ]
}
