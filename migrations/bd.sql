INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`) VALUES
(1, 'test@yahoo.fr', '[\"ROLE_ADMIN\"]', '$2y$13$PL4zvHz.ZJ60B7r1iYp5tuap6tyV9idDJ/UVHA2qDziQLT8csSdVm', 'John', 'Doe'),
(2, 'test2@yahoo.fr', '[\"ROLE_USER\"]', '$2y$13$LOFmTNX2V8kv0ysmzzhzDuu4EAtN3Zv8PO7pPTyNcVw4PVqaGH3HW', 'Jane', 'Doe');


INSERT INTO `category` (`id`, `user_id`, `name`, `image`) VALUES
(1, 1, 'electro', 'electro.png'),
(2, 1, 'folk', 'folk.png'),
(3, 1, 'r&b', 'r&b.png'),
(4, 1, 'urban', 'urban.png');


INSERT INTO `song` (`id`, `user_id`, `category_id`, `title`, `artist`, `url`, `image`) VALUES
(1, 1, 1, 'Haul', 'Christian Löffler', 'https://storage.googleapis.com/electro-playlist/Christian Löffler - Haul (feat. Mohna).mp3', 'Christian-Loffler.png'),
(2, 1, 1, 'Bleu lagon', 'Mansfield Tya', 'https://storage.googleapis.com/electro-playlist/Mansfield.TYA - Bleu lagon.mp3', 'Mansfield-Tya.png'),
(3, 1, 1, 'Cupa Cupa', 'Parra for Cuva', 'https://storage.googleapis.com/electro-playlist/Parra for Cuva - Cupa Cupa.mp3', 'Parra-for-Cuva.png'),
(4, 1, 1, "Won\'t forget you", 'Shouse', "https://storage.googleapis.com/electro-playlist/Shouse - Won\'t Forget You.mp3", 'Shouse.png'),
(5, 1, 1, 'Queens', 'The Blaze', 'https://storage.googleapis.com/electro-playlist/The Blaze - Queens.mp3', 'The-Blaze.png'),
(6, 1, 2, 'The Eye', 'Brandi Carlile', 'https://storage.googleapis.com/folk-playlist/Brandi Carlile - The Eye.mp3', 'brandi-carlile.png'),
(7, 1, 2, 'Helplessy Hoping', 'Crosby, Stills & Nash', 'https://storage.googleapis.com/folk-playlist/Crosby, Stills Nash - Helplessly Hoping.mp3', 'crosby-stills-nash.png'),
(8, 1, 2, 'How', 'Marcus Mumford','https://storage.googleapis.com/folk-playlist/Marcus Mumford - How.mp3', 'marcus-mumford.png'),
(9, 1, 2, 'Funeral', 'Phoebe Bridgers', 'https://storage.googleapis.com/folk-playlist/Phoebe Bridgers - Funeral.mp3  ', 'phoebe-bridgers.png'),
(10, 1, 2, 'Motion Sickness', 'Phoebe Bridgers', 'https://storage.googleapis.com/folk-playlist/Phoebe Bridgers - Motion Sickness.mp3  ', 'phoebe-bridgers.png'),
(11, 1, 3, 'Bust Your Window', 'Jazmine Sullivan', 'https://storage.googleapis.com/rnb-playlist/Jazmine Sullivan - Bust Your Windows.mp3', 'jazmine-sullivan-window.png'),
(12, 1, 3, 'Lost One', 'Jazmine Sullivan', 'https://storage.googleapis.com/rnb-playlist/Jazmine Sullivan - Lost One.mp3', 'jazmine-sullivan-heaux.png'),
(13, 1, 3, 'Dilemma', 'Nelly ft Kelly Rowland','https://storage.googleapis.com/rnb-playlist/Nelly - Dilemma ft. Kelly Rowland.mp3', 'nelly.png'),
(14, 1, 4, 'Fumée', 'Aloïse Sauvage', 'https://storage.googleapis.com/urban-playlist/Aloïse Sauvage - Fumée.mp3', 'aloise-sauvage.png'),
(15, 1, 4, 'Love', 'Aloïse Sauvage', 'https://storage.googleapis.com/urban-playlist/Aloïse Sauvage - Love.mp3', 'aloise-sauvage.png'),
(16, 1, 4, 'Soulages', 'Aloïse Sauvage', 'https://storage.googleapis.com/urban-playlist/Aloïse Sauvage - Soulages.mp3', 'aloise-sauvage.png'),
(17, 1, 4, 'TTTTT', 'Loud', 'https://storage.googleapis.com/urban-playlist/Loud - TTTTT.mp3', 'loud.png'),
(18, 1, 4, 'Feux', 'Poupie ft Jul', 'https://storage.googleapis.com/urban-playlist/Poupie - Feux (feat. Jul).mp3', 'poupie-feu.png'),
(19, 1, 4, 'Vue sur la mer', 'Poupie', 'https://storage.googleapis.com/urban-playlist/Poupie - Vue sur la mer.mp3', 'poupie-enfant-roi.png');
