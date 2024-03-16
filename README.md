# MyBookmarks

- [MyBookmarks](#mybookmarks)
  - [Introducción](#introducción)
    - [Esquema de la base de datos](#esquema-de-la-base-de-datos)
    - [Como colaborar](#como-colaborar)
  - [Cómo instalar](#cómo-instalar)
  - [Cómo usar](#cómo-usar)
  - [Testing](#testing)

## Introducción

(Descripcion de la aplicación y en qué consiste)

### Esquema de la base de datos

![Database Schema](./docs/mybookmarks_db.png)

### Como colaborar

1. Clonar el proyecto en tu equipo local.

2. Crear una nueva rama en la que se trabajará. El nombre de la rama debe describir el cambio que se está haciendo al proyecto. Por ejemplo: `añadir frontend` o `solucionar bug en frontend`.

    Si el colaborador lo desea, también puede añadir al nombre de la rama el tipo de cambio que está haciendo en base a los [tipos de commits convencionales](https://theodorusclarence.com/shorts/conventional-commit-readme). Por ejemplo: `feat/añadir frontend` o `fix/bug en frontend`.

3. Se harán commits cada vez que se termine de hacer un punto clave. Por ejemplo: si estás haciendo el frontend, hacer un commit cada vez que se termine de desarrollar una página, o al resolver un bug en una página ya creada.

   **¡IMPORTANTE!** Los commits se crearan siguiendo la convención especificada en está página: [Conventional Commits Readme](https://theodorusclarence.com/shorts/conventional-commit-readme). Los commits deberán incluir su tipo, el scope (qué carpetas o archivos han sido modificados/creados), y una descripción del commit. Los commits pueden ser en inglés o español, a preferencia del colaborador. Por ejemplo: `feat(resources): añadir frontend` o `fix(pagina.blade.php): bug en x página`. Para commits con una descripción más amplia:

   ```
   feat(resources): añadir frontend
   
   añadida página A
   añadida página B
   añadida página C
   ```

4. Una vez la tarea para la que se creo la rama sea terminada, se hará un `git pull` de la rama con la que se quiere fusionar (`main`) y se resolverá cualquier conflicto que pueda surgir. Se aconseja al colaborador que también ejecute todos los tests que puedan existir en el proyecto para asegurarse de que no haya roto nada por accidente.

5. Una vez los posible conflictos sean solucionados, se creará una Pull Request en GitHub (es necesario haber subido la rama y todos sus commits de antemano a GitHub) con nombre de la Pull Request, una breve descripción de los cambios realizados, y el propio colaborador asignará a un compañero del equipo como "asignee" para que revise su Pull Request.

   El asignee está en su derecho a solicitar al colaborador cualquier cambio que vea necesario después de revisar su Pull Request; en cuyo caso, el colaborador deberá realizar los cambios propuestos y avisar al asignee para que vuelva a revisar su Pull Request una vez realizados los cambios.

6. Una vez el asignee ha revisado la Pull Request y dado el visto bueno, el asignee fusionará la rama de la Pull Request con la rama `main`. La rama fusionada será borrada para evitar acumulación de ramas en el proyecto (si fuera necesario, la eliminación de la rama se puede revertir).

7. Si se encontrara algún bug en la rama `main` después de fusionarla con alguna rama, se creará un Issue en la sección "Issues" en el GitHub del proyecto explicando el problema y la posible causa. El colaborador causante del bug o cualquier otro es libre de asignarse así mismo el Issue y crear una rama `fix` para solucionar el bug. Se aconseja darle a "Watch" en el GitHub del proyecto para recibir una notificación cada vez que alguien comente o cree un Issue.

## Cómo instalar

(Instrucciones para instalar el proyecto en local. Link para acceder en remoto)

## Cómo usar

(Instrucciones de cómo usar la aplicación)

## Testing

(Resultados del testing)
