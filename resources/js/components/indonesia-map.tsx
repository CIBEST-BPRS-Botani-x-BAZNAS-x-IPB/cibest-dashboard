import { useEffect, useRef } from "react"
import { Province } from "@/types"

interface IndonesiaMapProps {
  provinces: Province[];
  povertyStandardId: number;
}

export function IndonesiaMap({ provinces, povertyStandardId }: IndonesiaMapProps) {
  const mapContainer = useRef<HTMLDivElement>(null)
  const mapInstance = useRef<any>(null)

  useEffect(() => {
    if (!mapContainer.current) return

    const link = document.createElement("link")
    link.rel = "stylesheet"
    link.href = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"
    document.head.appendChild(link)

    const script = document.createElement("script")
    script.src = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"
    script.async = true
    script.onload = () => {
      const L = (window as any).L
      if (!L) return

      const map = L.map(mapContainer.current, {
        scrollWheelZoom: false,
        dragging: true,
        touchZoom: true,
        doubleClickZoom: true,
        boxZoom: true,
        keyboard: true,
        zoomControl: true,
      }).setView([-2.5, 118], 5)
      
      mapInstance.current = map

      mapContainer.current?.addEventListener('mouseenter', () => {
        map.scrollWheelZoom.enable()
      })
      
      mapContainer.current?.addEventListener('mouseleave', () => {
        map.scrollWheelZoom.disable()
      })

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
        maxZoom: 19,
      }).addTo(map)

      // Load local GeoJSON file exported from QGIS
      const geojsonPath = '/data/prov.geojson'
      console.log("Fetching GeoJSON from:", geojsonPath)
      console.log("Full URL:", window.location.origin + geojsonPath)
      
      fetch(geojsonPath)
        .then(res => {
          console.log("Response status:", res.status, res.statusText)
          console.log("Response URL:", res.url)
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: Failed to load GeoJSON from ${res.url}`)
          }
          return res.json()
        })
        .then(data => {
          console.log("GeoJSON loaded:", data)
          const geoJsonLayer = L.geoJSON(data, {
            style: (feature: any) => {
              const geoProvinceName = (
                feature.properties.PROVINSI ||
                feature.properties.NAME_1 ||
                feature.properties.WADMPR ||
                feature.properties.name ||
                ""
              ).toLowerCase();

              // Find matching province in our data
              let matchingProvince = null;
              for (const prov of provinces) {
                // Compare province names with common variations
                const compareProvinceName = prov.name.toLowerCase();

                // Try exact match first
                if (compareProvinceName === geoProvinceName) {
                  matchingProvince = prov;
                  break;
                }

                // Try partial matches (this handles cases like "JAWA TENGAH" vs "Central Java")
                if (geoProvinceName.includes(compareProvinceName) || compareProvinceName.includes(geoProvinceName)) {
                  matchingProvince = prov;
                  break;
                }

                // Common variations and abbreviations
                if (compareProvinceName.includes("jawa") && geoProvinceName.includes("jawa")) {
                  matchingProvince = prov;
                  break;
                }
                if ((compareProvinceName.includes("sumatera") || compareProvinceName.includes("sumatra")) &&
                    (geoProvinceName.includes("sumatera") || geoProvinceName.includes("sumatra"))) {
                  matchingProvince = prov;
                  break;
                }
                if (compareProvinceName.includes("kalimantan") && geoProvinceName.includes("kalimantan")) {
                  matchingProvince = prov;
                  break;
                }
                if (compareProvinceName.includes("sulawesi") && geoProvinceName.includes("sulawesi")) {
                  matchingProvince = prov;
                  break;
                }
              }

              let fillColor = "#9ca3af"; // Default to gray if no match found (indicating no data)
              let quadrant = "No Data";

              if (matchingProvince) {
                const dominantQuadrant = matchingProvince.dominant;
                quadrant = dominantQuadrant;

                // Set color based on dominant quadrant
                switch(dominantQuadrant) {
                  case "Q1":
                    fillColor = "#22c55e"; // Green
                    break;
                  case "Q2":
                    fillColor = "#3b82f6"; // Blue
                    break;
                  case "Q3":
                    fillColor = "#eab308"; // Yellow
                    break;
                  case "Q4":
                    fillColor = "#ef4444"; // Red
                    break;
                  default:
                    fillColor = "#9ca3af"; // Gray as fallback
                }
              } else {
                fillColor = "#9ca3af"; // Gray to indicate no data
                quadrant = "No Data";
              }

              return {
                fillColor: fillColor,
                weight: 2,
                opacity: 1,
                color: "white",
                dashArray: '3',
                fillOpacity: 0.7
              }
            },
            onEachFeature: (feature: any, layer: any) => {
              const geoProvinceName =
                feature.properties.PROVINSI ||
                feature.properties.NAME_1 ||
                feature.properties.WADMPR ||
                feature.properties.name ||
                "Unknown";

              let matchingProvince = null;
              for (const prov of provinces) {
                const compareProvinceName = prov.name.toLowerCase();

                // Try exact match first
                if (compareProvinceName === geoProvinceName.toLowerCase()) {
                  matchingProvince = prov;
                  break;
                }

                // Try partial matches
                if (geoProvinceName.toLowerCase().includes(compareProvinceName) ||
                    compareProvinceName.includes(geoProvinceName.toLowerCase())) {
                  matchingProvince = prov;
                  break;
                }

                // Common variations and abbreviations
                if (compareProvinceName.includes("jawa") && geoProvinceName.toLowerCase().includes("jawa")) {
                  matchingProvince = prov;
                  break;
                }
                if ((compareProvinceName.includes("sumatera") || compareProvinceName.includes("sumatra")) &&
                    (geoProvinceName.toLowerCase().includes("sumatera") || geoProvinceName.toLowerCase().includes("sumatra"))) {
                  matchingProvince = prov;
                  break;
                }
                if (compareProvinceName.includes("kalimantan") && geoProvinceName.toLowerCase().includes("kalimantan")) {
                  matchingProvince = prov;
                  break;
                }
                if (compareProvinceName.includes("sulawesi") && geoProvinceName.toLowerCase().includes("sulawesi")) {
                  matchingProvince = prov;
                  break;
                }
              }

              let quadrant = "No Data";
              let color = "#9ca3af"; // Gray for no data

              if (matchingProvince) {
                const dominantQuadrant = matchingProvince.dominant;

                switch(dominantQuadrant) {
                  case "Q1":
                    quadrant = "Q1 - Sejahtera";
                    color = "#22c55e"; // Green
                    break;
                  case "Q2":
                    quadrant = "Q2 - Material";
                    color = "#3b82f6"; // Blue
                    break;
                  case "Q3":
                    quadrant = "Q3 - Spiritual";
                    color = "#eab308"; // Yellow
                    break;
                  case "Q4":
                    quadrant = "Q4 - Absolut";
                    color = "#ef4444"; // Red
                    break;
                }
              } else {
                quadrant = "No Data";
                color = "#9ca3af"; // Gray for no data
              }

              layer.bindPopup(
                `<strong>${geoProvinceName}</strong><br/>` +
                (matchingProvince ?
                  `<div style="margin-top: 8px;">` +
                  `<div><span style="color: #22c55e;">●</span> Q1: ${matchingProvince?.Q1 || 0}</div>` +
                  `<div><span style="color: #3b82f6;">●</span> Q2: ${matchingProvince?.Q2 || 0}</div>` +
                  `<div><span style="color: #eab308;">●</span> Q3: ${matchingProvince?.Q3 || 0}</div>` +
                  `<div><span style="color: #ef4444;">●</span> Q4: ${matchingProvince?.Q4 || 0}</div>` +
                  `</div>` +
                  `<div style="margin-top: 8px; font-weight: bold;">Dominan: ` +
                  `<span style="color: ${color};">${quadrant.split(' - ')[0]}</span></div>`
                :
                  `<div style="margin-top: 8px; color: #9ca3af; font-style: italic;">Tidak ada data tersedia</div>`
                )
              )

              layer.on('mouseover', function() {
                this.setStyle({
                  weight: 3,
                  color: '#666',
                  fillOpacity: 0.9,
                  dashArray: ''
                })
              })

              layer.on('mouseout', function() {
                this.setStyle({
                  weight: 2,
                  color: 'white',
                  fillOpacity: 0.7,
                  dashArray: '3'
                })
              })
            }
          }).addTo(map)

          // Fit map to show all provinces
          map.fitBounds(geoJsonLayer.getBounds())
        })
        .catch(err => {
          console.error("Error loading GeoJSON:", err)
          alert(
            "Failed to load map data.\n\n" +
            "Please ensure:\n" +
            "1. File exists at: public/data/prov.geojson\n" +
            "2. The file is valid GeoJSON format\n\n" +
            "Error: " + err.message
          )
        })
    }
    document.body.appendChild(script)

    return () => {
      if (mapInstance.current) {
        mapInstance.current.remove()
        mapInstance.current = null
      }
      document.head.removeChild(link)
      document.body.removeChild(script)
    }
  }, [])

  return (
    <div style={{ 
      width: "100%",
      backgroundColor: "white",
      borderRadius: "8px",
      boxShadow: "0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)",
      overflow: "hidden",
      marginTop: "24px",
      position: "relative",
      zIndex: 1
    }}>
      <div 
        style={{
          backgroundColor: "#f8fafc",
          padding: "20px 24px",
          borderBottom: "1px solid #e2e8f0"
        }}
      >
        <h2 
          style={{
            margin: 0,
            fontSize: "18px",
            fontWeight: "600",
            color: "#1e293b"
          }}
        >
          Peta Sebaran Responden
        </h2>
      </div>
      <div 
        ref={mapContainer} 
        style={{ 
          width: "100%", 
          height: "500px",
          position: "relative"
        }} 
      />
    </div>
  )
}