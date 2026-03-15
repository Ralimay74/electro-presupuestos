<div style="padding: 20px;">
    <p style="margin-bottom: 15px;">Enlace público para compartir con el cliente:</p>
    
    <div style="display: flex; gap: 10px; align-items: center;">
        <input 
            type="text" 
            value="{{ $url }}" 
            readonly 
            style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
            id="publicUrl"
        />
        <button 
            onclick="navigator.clipboard.writeText(document.getElementById('publicUrl').value); alert('¡Copiado!');"
            style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"
        >
            📋 Copiar
        </button>
    </div>
    
    <p style="margin-top: 15px; font-size: 12px; color: #6b7280;">
        ⚠️ Este enlace será válido por 30 días o hasta que lo desactives.
    </p>
</div>