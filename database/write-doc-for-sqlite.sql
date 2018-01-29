DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `salt` varchar(50) NOT NULL DEFAULT '',
  `created_at` varchar(20),
  `updated_at` varchar(20)
);

INSERT INTO `user`(username, email, password, salt, created_at, updated_at)
VALUES ('admin', 'admin@admin.com', '5cf9760f6be7c8d3e7108d90a6523487', 'f0ee86d997d102ab94e4bba1f5cbd3ea', '2018-01-23 17:54:30', '2018-01-23 17:54:30');