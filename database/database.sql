-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(128) NOT NULL UNIQUE,
    `password` varchar(128),
    `name` varchar(200) NOT NULL,
    `address` varchar(255) DEFAULT NULL,
    `phone` varchar(64) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` (`id`, `email`, `password`, `name`, `address`, `phone`)
VALUES (1,'kiennt85@gmail.com','c70b5dd9ebfb6f51d09d4132b7170c9d20750a7852f00680f65658f0310e810056e6763c34c9a00b0e940076f54495c169fc2302cceb312039271c43469507dc',
        'trungkien', '355 xuan dinh', '0988640636');

INSERT INTO `users` (`id`, `email`, `password`, `name`, `address`, `phone`)
VALUES (2,'admin@gmail.com','c70b5dd9ebfb6f51d09d4132b7170c9d20750a7852f00680f65658f0310e810056e6763c34c9a00b0e940076f54495c169fc2302cceb312039271c43469507dc',
        'admin', '999 My Dinh', '0988640638');
