module.exports = {
  // frontendでビルドして出力されたアセットが、Laravelのどこの場所に作成されるのかを設定する
  // Laravelの `public` の `app` ディレクトリ配下に作成されるようにする
  outputDir: '../public/app',

  // 配置したいサブディレクトリを設定する
  // Laravelのpublic/app配下にjs, cssなどが置かれるので、階層をappに合わせるためにpublicPathを調整する
  publicPath: '/app',

  pages: {
    // appのエントリポイント、テンプレート、出力先を調整
    app: {
      // エントリーポイント（一番最初に実行される）となるファイル
      entry: 'src/app/main.js',
      // 読み込まれるテンプレートを設定。デフォルトはpublic/index.html
      template: 'templates/base.html',
      // 画面HTML のファイルパスを指定
      filename: '../../resources/views/spa/app.blade.php',
    },
  },
};
