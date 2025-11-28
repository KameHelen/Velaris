Diagrama de clases (PlantUML)

Archivos:
- `docs/uml/class_diagram.puml` : archivo PlantUML con el diagrama de clases simplificado.

Renderizar:
Puedes usar PlantUML localmente o el servidor online.

Opción local (requiere Java y PlantUML jar):

```powershell
# desde la carpeta del repo
java -jar plantuml.jar docs\uml\class_diagram.puml
```

Opción rápida (online): copia el contenido de `class_diagram.puml` y pégalo en https://www.plantuml.com/plantuml/ o usa un plugin de tu editor que soporte PlantUML.

Notas:
- El diagrama está simplificado: muestra las clases principales `Database`, `Post`, `User` y los controladores.
- Si quieres, puedo renderizar el PNG aquí (si me indicas que quieres que lo genere localmente), o mejorar el detalle del diagrama (por ejemplo añadir relaciones con tablas `reactions` o `saved_posts`).
