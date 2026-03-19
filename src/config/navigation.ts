export interface NavItem {
  label: string;
  page: string;
  hash?: string;
  children?: NavItem[];
}

export interface CtaConfig {
  label: string;
  href: string;
}

export interface NavConfig {
  items: NavItem[];
  ctas: {
    edith: CtaConfig;
    whatsapp: CtaConfig;
  };
}

export function validateNavItems(items: NavItem[]): void {
  items.forEach(item => {
    if (!item.label || !item.page) {
      throw new Error(`Invalid nav item: ${JSON.stringify(item)}`);
    }
    if (item.children) {
      validateNavItems(item.children);
    }
  });
}

export const navItems: NavItem[] = [
  {
    label: "Conoce al equipo",
    page: "/",
    children: [
      { label: "Quiénes somos", page: "/", hash: "#quienes-somos" },
      { label: "Nuestra historia", page: "/", hash: "#nuestra-historia" },
      { label: "Nuestros medicos", page: "/", hash: "#nuestros-medicos" },
      { label: "Mision, vision y valores", page: "/", hash: "#mision-vision-valores" }
    ]
  },
  {
    label: "Enfermedades que tratamos",
    page: "/especialidades",
    children: [
      { label: "Artrosis", page: "/especialidades", hash: "#artrosis-osteoartrosis" },
      { label: "Artritis Reumatoide", page: "/especialidades", hash: "#artritis-reumatoide" },
      { label: "Fibromialgia", page: "/especialidades", hash: "#fibromialgia" },
      { label: "Espondilitis Anquilosante", page: "/especialidades", hash: "#espondilitis-anquilosante" },
      { label: "Reumatismos de Partes Blandas", page: "/especialidades", hash: "#reumatismos-de-partes-blandas" }
    ]
  },
  {
    label: "Tratamiento y seguimiento",
    page: "/tratamiento-medico-integral",
    children: [
      { label: "Diagnóstico", page: "/tratamiento-medico-integral", hash: "#diagnostico" },
      { label: "Paiper", page: "/tratamiento-medico-integral", hash: "#paiper" }
    ]
  },
  {
    label: "Recursos para pacientes",
    page: "/club-vida-y-salud",
    children: []
  },
  {
    label: "Contacto y cita",
    page: "/contactanos",
    children: [
      { label: "Contacto", page: "/contactanos", hash: "#contactanos" },
      { label: "Testimonios", page: "/contactanos", hash: "#testimonios" }
    ]
  }
];

export const navConfig: NavConfig = {
  items: navItems,
  ctas: {
    edith: {
      label: "Agendar valoracion",
      href: "/contactanos#contactanos"
    },
    whatsapp: {
      label: "WhatsApp para cita",
      href: "https://wa.me/525559890607?text=Hola%2C%20quiero%20agendar%20una%20consulta%20de%20valoracion."
    }
  }
};
