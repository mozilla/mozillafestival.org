const Utils = {
  slugify: function(str) {
    return str.replace(/[^A-Za-z0-9]/g, `-`).toLowerCase();
  }
};

module.exports = Utils;
