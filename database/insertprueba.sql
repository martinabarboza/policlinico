INSERT INTO `usuarios`
(`id_usuario`, `cedula_usuario`, `nombre_usuario`, `apellido_usuario`, `email_usuario`, `passwd_usuario`, `lastlogin_usuario`, `dateupdate_usuario`, `rol_usuario`)
VALUES
(1, '12345678', 'Admin', 'Rastro', 'admin@policlinico.test', '$2y$10$D2qNc1xL38NEE86/Mn.S5.DYGrCKrgnA112eJ.cXf2Tr6GyMXAgZa', NOW(), NOW(), 'ADMIN');

--- no puede ser not null last login y dateupdate quizas si
