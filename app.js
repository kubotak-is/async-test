require('babel-register');

const compress = require('koa-compress');
const router   = require('koa-router');
const koa      = require('koa');
const app      = new koa();
const route    = new router();

route.get('/', (ctx, next) => {
  ctx.body = {
    'msg': 'hello' 
  };
});

// 1sec wait endpoint
route.get('/test/:id', async (ctx, next) => {
  console.log('生');
  await sleep(500);
  console.log('ハム');
  await sleep(500);
  console.log('🍈');
  ctx.body = {
    'id': parseInt(ctx.params.id)
  };
});

function sleep(time) {
  return new Promise((resolve, reject) => {
      setTimeout(() => {
          resolve();
      }, time);
  });
}

app.use(route.routes())
  .use(route.allowedMethods());
app.use(compress());

if (!module.parent) {
  app.listen(3000);
  console.log('listening on port 3000');
}