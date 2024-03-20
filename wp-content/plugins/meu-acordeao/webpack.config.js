const path = require('path');

module.exports = {
  entry: './bloco/index.js', // Caminho do seu arquivo de entrada
  output: {
    path: path.resolve(__dirname, 'bloco'), // Caminho da pasta de saída
    filename: 'index.build.js' // Nome do arquivo compilado
  },
  module: {
    rules: [
      {
        test: /\.js$/, // Regra para arquivos .js
        exclude: /node_modules/, // Ignorar node_modules
        use: {
          loader: 'babel-loader', // Usar babel-loader para processar os arquivos
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'] // Presets do Babel
          }
        }
      }
    ]
  }
};
