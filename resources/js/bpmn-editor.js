import Modeler from 'bpmn-js/lib/Modeler';
import 'bpmn-js/dist/assets/diagram-js.css';
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn-embedded.css';

// Default BPMN diagram template
const DEFAULT_BPMN_XML = `<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL"
                  xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI"
                  xmlns:dc="http://www.omg.org/spec/DD/20100524/DC"
                  xmlns:di="http://www.omg.org/spec/DD/20100524/DI"
                  targetNamespace="http://bpmn.io/schema/bpmn"
                  id="Definitions_1">
  <bpmn:process id="Process_1" isExecutable="false">
    <bpmn:startEvent id="StartEvent_1" name="Start">
      <bpmn:outgoing>Flow_1</bpmn:outgoing>
    </bpmn:startEvent>
    <bpmn:task id="Task_1" name="Sample Task">
      <bpmn:incoming>Flow_1</bpmn:incoming>
      <bpmn:outgoing>Flow_2</bpmn:outgoing>
    </bpmn:task>
    <bpmn:endEvent id="EndEvent_1" name="End">
      <bpmn:incoming>Flow_2</bpmn:incoming>
    </bpmn:endEvent>
    <bpmn:sequenceFlow id="Flow_1" sourceRef="StartEvent_1" targetRef="Task_1"/>
    <bpmn:sequenceFlow id="Flow_2" sourceRef="Task_1" targetRef="EndEvent_1"/>
  </bpmn:process>
  <bpmndi:BPMNDiagram id="BPMNDiagram_1">
    <bpmndi:BPMNPlane id="BPMNPlane_1" bpmnElement="Process_1">
      <bpmndi:BPMNShape id="StartEvent_1_di" bpmnElement="StartEvent_1">
        <dc:Bounds x="180" y="100" width="36" height="36"/>
        <bpmndi:BPMNLabel>
          <dc:Bounds x="186" y="143" width="24" height="14"/>
        </bpmndi:BPMNLabel>
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="Task_1_di" bpmnElement="Task_1">
        <dc:Bounds x="300" y="78" width="100" height="80"/>
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="EndEvent_1_di" bpmnElement="EndEvent_1">
        <dc:Bounds x="480" y="100" width="36" height="36"/>
        <bpmndi:BPMNLabel>
          <dc:Bounds x="488" y="143" width="20" height="14"/>
        </bpmndi:BPMNLabel>
      </bpmndi:BPMNShape>
      <bpmndi:BPMNEdge id="Flow_1_di" bpmnElement="Flow_1">
        <di:waypoint x="216" y="118"/>
        <di:waypoint x="300" y="118"/>
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNEdge id="Flow_2_di" bpmnElement="Flow_2">
        <di:waypoint x="400" y="118"/>
        <di:waypoint x="480" y="118"/>
      </bpmndi:BPMNEdge>
    </bpmndi:BPMNPlane>
  </bpmndi:BPMNDiagram>
</bpmn:definitions>`;

class BpmnEditor {
    constructor() {
        this.modeler = null;
        this.currentDiagramId = window.diagramId || null;
        this.init();
    }

    async init() {
        // Initialize BPMN Modeler
        this.modeler = new Modeler({
            container: '#canvas',
            keyboard: {
                bindTo: document
            }
        });

        // Load existing diagram or create new one
        const existingXml = window.existingXml || null;
        if (existingXml) {
            await this.loadDiagram(existingXml);
        } else {
            await this.createNewDiagram();
        }

        // Setup event listeners
        this.setupEventListeners();

        // Track changes
        this.modeler.on('commandStack.changed', () => {
            console.log('Diagram modified');
        });
    }

    async loadDiagram(xml) {
        try {
            const { warnings } = await this.modeler.importXML(xml);
            
            if (warnings.length) {
                console.warn('Import warnings:', warnings);
            }

            // Fit viewport
            const canvas = this.modeler.get('canvas');
            canvas.zoom('fit-viewport');

            console.log('Diagram loaded successfully');
        } catch (err) {
            console.error('Error loading diagram:', err);
            alert('Error loading diagram: ' + err.message);
        }
    }

    async createNewDiagram() {
        try {
            await this.modeler.importXML(DEFAULT_BPMN_XML);
            
            const canvas = this.modeler.get('canvas');
            canvas.zoom('fit-viewport');

            console.log('New diagram created');
        } catch (err) {
            console.error('Error creating diagram:', err);
            alert('Error creating diagram: ' + err.message);
        }
    }

    setupEventListeners() {
        // Save button
        document.getElementById('saveBtn').addEventListener('click', () => {
            this.saveDiagram();
        });

        // Deploy to Camunda button
        const deployBtn = document.getElementById('deployBtn');
        if (deployBtn) {
            deployBtn.addEventListener('click', () => {
                this.deployToCamunda();
            });
        }

        // Export button
        document.getElementById('exportBtn').addEventListener('click', () => {
            this.exportDiagram();
        });

        // New diagram button
        document.getElementById('newBtn').addEventListener('click', () => {
            if (confirm('Create a new diagram? Any unsaved changes will be lost.')) {
                this.createNewDiagram();
                this.currentDiagramId = null;
                document.getElementById('diagramId').value = '';
                document.getElementById('diagramName').value = '';
                document.getElementById('diagramDescription').value = '';
            }
        });

        // Undo/Redo
        document.getElementById('undoBtn').addEventListener('click', () => {
            const commandStack = this.modeler.get('commandStack');
            commandStack.undo();
        });

        document.getElementById('redoBtn').addEventListener('click', () => {
            const commandStack = this.modeler.get('commandStack');
            commandStack.redo();
        });

        // Zoom controls
        document.getElementById('zoomInBtn').addEventListener('click', () => {
            const canvas = this.modeler.get('canvas');
            canvas.zoom(canvas.zoom() + 0.1);
        });

        document.getElementById('zoomOutBtn').addEventListener('click', () => {
            const canvas = this.modeler.get('canvas');
            canvas.zoom(canvas.zoom() - 0.1);
        });

        document.getElementById('fitViewBtn').addEventListener('click', () => {
            const canvas = this.modeler.get('canvas');
            canvas.zoom('fit-viewport');
        });
    }

    async deployToCamunda() {
        try {
            const name = document.getElementById('diagramName').value.trim() || 'Untitled';
            const { xml } = await this.modeler.saveXML({ format: true });

            const response = await fetch(window.routeDeploy, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    xml_content: xml,
                    name: name
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('✅ Process deployed to Camunda successfully!');
                console.log('Deployment result:', result.data);
            } else {
                alert('❌ Deployment failed: ' + result.message);
            }
        } catch (err) {
            console.error('Deployment error:', err);
            alert('❌ Error deploying to Camunda: ' + err.message + '\n\nMake sure Camunda is running (docker compose up -d)');
        }
    }

    async saveDiagram() {
        try {
            const name = document.getElementById('diagramName').value.trim();
            
            if (!name) {
                alert('Please enter a diagram name');
                return;
            }

            // Export XML
            const { xml } = await this.modeler.saveXML({ format: true });

            const data = {
                name: name,
                description: document.getElementById('diagramDescription').value.trim(),
                xml_content: xml,
                version: '1.0'
            };

            let url, method;
            if (this.currentDiagramId) {
                // Update existing diagram
                url = window.routeUpdate || `/api/bpmn/${this.currentDiagramId}`;
                method = 'PUT';
            } else {
                // Create new diagram
                url = window.routeStore || '/api/bpmn';
                method = 'POST';
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert('Diagram saved successfully!');
                
                // Update diagram ID if it was a new diagram
                if (!this.currentDiagramId && result.diagram) {
                    this.currentDiagramId = result.diagram.id;
                    document.getElementById('diagramId').value = result.diagram.id;
                    
                    // Update URL without reloading
                    window.history.pushState({}, '', `/bpmn/${result.diagram.id}/edit`);
                }
            } else {
                alert('Error saving diagram: ' + (result.message || 'Unknown error'));
            }
        } catch (err) {
            console.error('Error saving diagram:', err);
            alert('Error saving diagram: ' + err.message);
        }
    }

    async exportDiagram() {
        try {
            const name = document.getElementById('diagramName').value.trim() || 'diagram';
            const { xml } = await this.modeler.saveXML({ format: true });

            // Create download link
            const blob = new Blob([xml], { type: 'application/xml' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${name.replace(/\s+/g, '_')}.bpmn`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            console.log('Diagram exported');
        } catch (err) {
            console.error('Error exporting diagram:', err);
            alert('Error exporting diagram: ' + err.message);
        }
    }
}

// Initialize editor when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new BpmnEditor();
    });
} else {
    new BpmnEditor();
}
