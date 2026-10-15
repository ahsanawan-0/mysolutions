# Homepage photography

Created on 15 October 2026 with the built-in image_gen tool, one image per call (10 images total). These are AI-generated illustrative scenes, not actual company staff, clients or premises.

Original PNG folder: `D:\mysolutions\New folder\assets-src\images`

| Original file | Website use |
|---|---|
| home/hero-building.png | Hero and homepage social sharing image |
| home/tender-consultancy-meeting.png | Approach, bid management, decorative numeral and process |
| services/digital-presence-web-designer.png | Digital Presence and process |
| sectors/healthcare-social-care-consultation.png | Healthcare, compliance service and insight cards |
| sectors/transport-passenger-minibus.png | Transport |
| sectors/commercial-office-cleaning.png | Cleaning |
| sectors/education-adult-training-workshop.png | Education and process |
| sectors/security-office-reception.png | Security |
| sectors/facilities-manager-plant-room.png | Facilities Management |
| sectors/building-maintenance-engineer.png | Building Maintenance |

Full prompts: `GENERATION-PROMPTS.json` in this folder. Art direction: clean editorial photography, natural lighting, cool blue-grey grading and navy accents, without logos or added text.

Run `npm run images` to create responsive AVIF, WebP and JPEG copies under `public/assets/img` and update the image manifest. Run `npm run og` to regenerate the homepage sharing crop.
