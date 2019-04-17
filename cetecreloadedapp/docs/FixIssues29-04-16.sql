SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE questions
ADD CONSTRAINT fk_id_exam
FOREIGN KEY (id_topic)
REFERENCES topics (id_topic)
ON DELETE CASCADE;

ALTER TABLE opciones
ADD CONSTRAINT fk_id_question
FOREIGN KEY (id_question)
REFERENCES questions (id_question)
ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;

delete from questions where id_topic != 269;

delete from opciones where id_opcion >= 566;

SELECT * FROM `contents` WHERE content like '%gallery%';

update contents set content = '<p><div style="display:none;" class="html5gallery" data-skin="gallery" data-width="480" data-height="272"> &nbsp;<br />&nbsp;<a href="https://youtu.be/VEisWU-Mxq8"><img src="http://img.youtube.com/vi/VEisWU-Mxq8/hqdefault.jpg" alt=""></a><br />&nbsp;<a href="https://youtu.be/HT0WNqBCbvg"><img src="http://img.youtube.com/vi/HT0WNqBCbvg/hqdefault.jpg" alt=""></a><br />&nbsp;</div></p><p>&nbsp;</p>' where id_content = 340;

update contents set type = 'multimedia' where id_content = 340;


update contents set content = '<div style="display:none;" class="html5gallery" data-skin="gallery" data-width="480" data-height="272"> &nbsp;<br />&nbsp;<a href="https://youtu.be/PyZKpw8T_gU"><img src="http://img.youtube.com/vi/PyZKpw8T_gU/hqdefault.jpg" alt=""></a><br />&nbsp;</div></p>' where id_content = 342;

update contents set type = 'multimedia' where id_content = 342;
