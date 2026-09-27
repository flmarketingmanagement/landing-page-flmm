"""Ajustes SEO por servicio (palabra clave de Rank Math, título, descripción, entrada, H1, títulos H2, guía y referencia).

Cada archivo services_seo_*.py define SEO = {slug: dict(...)}; aquí se unen. Formato (tuplas (ES, EN)):
  kw, title, desc, lead, h1, h_inc, h_faq: (es, en)
  guide: dict(label=(es, en), h2=(es, en), intro=(es, en), items=[(h3_es, h3_en, p_es, p_en), ...])
  ref: ((label_es, label_en), url) o None
"""
import importlib, os, sys
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
SEO = {}
for _m in ('services_seo_a', 'services_seo_b'):
    try:
        SEO.update(importlib.import_module(_m).SEO)
    except ModuleNotFoundError:
        pass
