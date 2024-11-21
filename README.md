# Пример кастомного модуля

Устанавливаем модуль

![image](https://github.com/user-attachments/assets/703e4407-c514-40a7-bca6-114ea61447fc)

## Функционал

<details> 
  <summary>Блокировка изменения ответственного по группе пользователя</summary>

  Разрешенные группы (к кому не применяется блокировка) задаются в настройках модуля:
  
  ![image](https://github.com/user-attachments/assets/bec7723b-3989-4313-9624-ea2f56d75f56)
  
  
  Блокировка происходит как в интерфейсе пользователя
  
  ![image](https://github.com/user-attachments/assets/df0cd27c-1524-42e8-98de-d1fdf47e0092)
  
  
  Так и на стороне сервера. Тогда пользователю придет подобное уведомление:
  
  ![image](https://github.com/user-attachments/assets/df87871b-b9d3-4dfb-b582-9238f9caa13f)

</details>

<details>
  <summary>Пример вызова компонента во вкладке карточки лида</summary>
  При открытии карточки лида при наличии прав пользователя на чтение добавляется кастомный таб.

  ![image](https://github.com/user-attachments/assets/8de74c4f-933f-4464-9f91-726159dcde97)

  Содержимое таба выводит свой компонент вывода данных из hl-блока

  ![image](https://github.com/user-attachments/assets/fefbb799-84fb-4296-afbc-d2ec4e1329c4)

  
</details>

