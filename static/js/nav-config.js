function deepFreeze(value) {
  if (!value || typeof value !== "object" || Object.isFrozen(value)) {
    return value;
  }

  Object.getOwnPropertyNames(value).forEach(function (key) {
    deepFreeze(value[key]);
  });

  return Object.freeze(value);
}

window.ARTRICENTER_NAV = deepFreeze({
  items: [
    {
      label: "Conoce al equipo",
      page: "quienes-somos.html",
      children: [
        {
          label: "Quiénes somos",
          page: "quienes-somos.html",
          hash: "#quienes-somos"
        },
        {
          label: "Nuestra historia",
          page: "quienes-somos.html",
          hash: "#nuestra-historia"
        },
        {
          label: "Nuestros medicos",
          page: "quienes-somos.html",
          hash: "#nuestros-medicos"
        },
        {
          label: "Mision, vision y valores",
          page: "quienes-somos.html",
          hash: "#mision-vision-valores"
        }
      ]
    },
    {
      label: "Enfermedades que tratamos",
      page: "especialidades.html",
      children: [
        {
          label: "Artrosis",
          page: "especialidades.html",
          hash: "#artrosis-osteoartrosis"
        },
        {
          label: "Artritis Reumatoide",
          page: "especialidades.html",
          hash: "#artritis-reumatoide"
        },
        {
          label: "Fibromialgia",
          page: "especialidades.html",
          hash: "#fibromialgia"
        },
        {
          label: "Espondilitis Anquilosante",
          page: "especialidades.html",
          hash: "#espondilitis-anquilosante"
        },
        {
          label: "Reumatismos de Partes Blandas",
          page: "especialidades.html",
          hash: "#reumatismos-de-partes-blandas"
        }
      ]
    },
    {
      label: "Tratamiento y seguimiento",
      page: "tratamiento-medico-integral.html",
      children: [
        {
          label: "Diagnóstico",
          page: "tratamiento-medico-integral.html",
          hash: "#diagnostico"
        },
        {
          label: "Paiper",
          page: "tratamiento-medico-integral.html",
          hash: "#paiper"
        }
      ]
    },
    {
      label: "Recursos para pacientes",
      page: "club-vida-y-salud.html",
      children: []
    },
    {
      label: "Contacto y cita",
      page: "contactanos.html",
      children: [
        {
          label: "Contacto",
          page: "contactanos.html",
          hash: "#contactanos"
        },
        {
          label: "Testimonios",
          page: "contactanos.html",
          hash: "#testimonios"
        }
      ]
    }
  ],
  ctas: {
    edith: {
      label: "Agendar valoracion",
      href: "contactanos.html#contactanos"
    },
    whatsapp: {
      label: "WhatsApp para cita",
      href: "https://wa.me/525559890607?text=Hola%2C%20quiero%20agendar%20una%20consulta%20de%20valoracion."
    }
  }
});
