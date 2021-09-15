const options = {
  production: process.env.NODE_ENV === 'production',
  analyze: process.env.ANALYZE
}

module.exports = options
