import 'package:flutter/material.dart';

void main() {
  runApp(const SanLuisApp());
}

class SanLuisApp extends StatelessWidget {
  const SanLuisApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'San Luis | Seguimiento académico',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFFF5F7FB),
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF4778ED),
          primary: const Color(0xFF4778ED),
          surface: Colors.white,
        ),
        fontFamily: 'Roboto',
      ),
      home: const DashboardScreen(),
    );
  }
}

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;

  static const _navItems = [
    _NavItem('Inicio', Icons.space_dashboard_outlined),
    _NavItem('Progreso', Icons.auto_graph_rounded),
    _NavItem('Alertas', Icons.notifications_none_rounded),
    _NavItem('Familias', Icons.forum_outlined),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        surfaceTintColor: Colors.white,
        titleSpacing: 20,
        title: const Row(
          children: [
            _BrandMark(),
            SizedBox(width: 10),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'San Luis Gonzaga',
                  style: TextStyle(
                    color: Color(0xFF243148),
                    fontSize: 15,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                Text(
                  'SEGUIMIENTO ACADÉMICO',
                  style: TextStyle(
                    color: Color(0xFF99A2B1),
                    fontSize: 8,
                    letterSpacing: 0.8,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          ],
        ),
        actions: [
          IconButton(
            onPressed: () {},
            tooltip: 'Notificaciones',
            icon: const Icon(Icons.notifications_none_rounded),
          ),
          const Padding(
            padding: EdgeInsets.only(right: 16),
            child: CircleAvatar(
              radius: 16,
              backgroundColor: Color(0xFF6C73B8),
              child: Text(
                'MR',
                style: TextStyle(color: Colors.white, fontSize: 10),
              ),
            ),
          ),
        ],
      ),
      body: SafeArea(
        top: false,
        child: IndexedStack(
          index: _selectedIndex,
          children: [
            _HomeTab(onReport: _showReportForm),
            _ProgressTab(),
            _AlertsTab(),
            _FamiliesTab(),
          ],
        ),
      ),
      bottomNavigationBar: NavigationBar(
        selectedIndex: _selectedIndex,
        onDestinationSelected: (index) {
          setState(() => _selectedIndex = index);
        },
        destinations: _navItems
            .map(
              (item) => NavigationDestination(
                icon: Icon(item.icon),
                label: item.label,
              ),
            )
            .toList(),
      ),
    );
  }

  void _showReportForm() {
    final messenger = ScaffoldMessenger.of(context);
    showModalBottomSheet<void>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => _ReportFormSheet(
        onSubmit: () {
          Navigator.of(context).pop();
          messenger.showSnackBar(
            const SnackBar(
              content: Text('Demostración: el reporte no se guardó ni se envió.'),
            ),
          );
        },
      ),
    );
  }
}

class _HomeTab extends StatelessWidget {
  const _HomeTab({required this.onReport});

  final VoidCallback onReport;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 22, 16, 24),
      children: [
        const Text(
          'I.E. SEMINARIO SAN LUIS GONZAGA',
          style: TextStyle(
            color: Color(0xFF96A0B0),
            fontSize: 9,
            letterSpacing: 1,
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 8),
        const Text(
          '¡Buenos días, María! ✦',
          style: TextStyle(
            color: Color(0xFF202D41),
            fontSize: 22,
            fontWeight: FontWeight.w800,
          ),
        ),
        const SizedBox(height: 5),
        const Text(
          'Resumen del progreso de tu comunidad educativa.',
          style: TextStyle(color: Color(0xFF8A95A6), fontSize: 12),
        ),
        const SizedBox(height: 20),
        const Wrap(
          spacing: 10,
          runSpacing: 10,
          children: [
            _MetricCard(
              title: 'Estudiantes',
              value: '1,248',
              detail: 'matriculados',
              icon: Icons.groups_2_outlined,
              color: Color(0xFF4778ED),
              background: Color(0xFFEFF4FF),
            ),
            _MetricCard(
              title: 'Promedio general',
              value: '15.6',
              detail: 'de 20 puntos',
              icon: Icons.auto_graph_rounded,
              color: Color(0xFF37A17C),
              background: Color(0xFFE9F7F0),
            ),
            _MetricCard(
              title: 'Alertas activas',
              value: '08',
              detail: 'requieren seguimiento',
              icon: Icons.notifications_active_outlined,
              color: Color(0xFFD19932),
              background: Color(0xFFFFF5DF),
            ),
            _MetricCard(
              title: 'Asistencia',
              value: '94.2%',
              detail: 'promedio semanal',
              icon: Icons.fact_check_outlined,
              color: Color(0xFF8E6ACC),
              background: Color(0xFFF2EDFC),
            ),
          ],
        ),
        const SizedBox(height: 22),
        const _SectionTitle(
          title: 'Alertas tempranas',
          action: 'Ver todas',
        ),
        const SizedBox(height: 10),
        const _AlertCard(
          initials: 'MQ',
          student: 'Mateo Quispe Rojas',
          detail: 'Promedio descendió en Matemática · 2.° B',
          severity: 'Urgente',
          color: Color(0xFFC75D5D),
          background: Color(0xFFFFF0EF),
        ),
        const SizedBox(height: 8),
        const _AlertCard(
          initials: 'VH',
          student: 'Valeria Huamán Díaz',
          detail: '3 inasistencias esta semana · 4.° A',
          severity: 'Atención',
          color: Color(0xFFA97C2E),
          background: Color(0xFFFFF7E6),
        ),
        const SizedBox(height: 18),
        const _SectionTitle(
          title: 'Rendimiento académico',
          action: 'Este periodo',
        ),
        const SizedBox(height: 10),
        const _AverageCard(),
        const SizedBox(height: 18),
        const _SectionTitle(title: 'Accesos rápidos'),
        const SizedBox(height: 10),
        const Row(
          children: [
            Expanded(
              child: _QuickAction(
                icon: Icons.person_search_outlined,
                title: 'Estudiantes',
                color: Color(0xFF4778ED),
              ),
            ),
            SizedBox(width: 9),
            Expanded(
              child: _QuickAction(
                icon: Icons.favorite_border_rounded,
                title: 'Convivencia',
                color: Color(0xFFB276AE),
                onTap: onReport,
              ),
            ),
            SizedBox(width: 9),
            Expanded(
              child: _QuickAction(
                icon: Icons.forum_outlined,
                title: 'Familias',
                color: Color(0xFF37A17C),
              ),
            ),
          ],
        ),
      ],
    );
  }
}

class _ProgressTab extends StatelessWidget {
  const _ProgressTab();

  @override
  Widget build(BuildContext context) {
    return const _PlaceholderTab(
      icon: Icons.auto_graph_rounded,
      title: 'Rendimiento académico',
      description:
          'Consulta calificaciones, promedios y evolución por periodo escolar.',
      message: 'El detalle de cursos y periodos se conectará al sistema escolar.',
    );
  }
}

class _AlertsTab extends StatelessWidget {
  const _AlertsTab();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 22, 16, 24),
      children: const [
        Text(
          'Seguimiento oportuno',
          style: TextStyle(
            color: Color(0xFF202D41),
            fontSize: 21,
            fontWeight: FontWeight.w800,
          ),
        ),
        SizedBox(height: 5),
        Text(
          'Avisos de rendimiento, asistencia y convivencia.',
          style: TextStyle(color: Color(0xFF8A95A6), fontSize: 12),
        ),
        SizedBox(height: 20),
        _AlertCard(
          initials: 'MQ',
          student: 'Mateo Quispe Rojas',
          detail: 'Promedio descendió en Matemática · 2.° B',
          severity: 'Urgente',
          color: Color(0xFFC75D5D),
          background: Color(0xFFFFF0EF),
        ),
        SizedBox(height: 9),
        _AlertCard(
          initials: 'VH',
          student: 'Valeria Huamán Díaz',
          detail: '3 inasistencias esta semana · 4.° A',
          severity: 'Atención',
          color: Color(0xFFA97C2E),
          background: Color(0xFFFFF7E6),
        ),
        SizedBox(height: 9),
        _AlertCard(
          initials: 'DF',
          student: 'Diego Flores León',
          detail: 'Rendimiento bajo en 2 cursos · 1.° C',
          severity: 'Atención',
          color: Color(0xFFA97C2E),
          background: Color(0xFFFFF7E6),
        ),
      ],
    );
  }
}

class _FamiliesTab extends StatelessWidget {
  const _FamiliesTab();

  @override
  Widget build(BuildContext context) {
    return const _PlaceholderTab(
      icon: Icons.forum_outlined,
      title: 'Escuela de padres',
      description:
          'Un espacio de comunicación entre las familias y la comunidad educativa.',
      message: 'Los comunicados y conversaciones estarán disponibles aquí.',
    );
  }
}

class _ReportFormSheet extends StatefulWidget {
  const _ReportFormSheet({required this.onSubmit});

  final VoidCallback onSubmit;

  @override
  State<_ReportFormSheet> createState() => _ReportFormSheetState();
}

class _ReportFormSheetState extends State<_ReportFormSheet> {
  final _formKey = GlobalKey<FormState>();
  final _descriptionController = TextEditingController();
  String _incidentType = 'Verbal';
  bool _anonymous = true;

  @override
  void dispose() {
    _descriptionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: MediaQuery.viewInsetsOf(context).bottom),
      child: Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
        ),
        child: SafeArea(
          top: false,
          child: SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 20),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Center(
                    child: Container(
                      width: 38,
                      height: 4,
                      decoration: BoxDecoration(
                        color: const Color(0xFFE2E6ED),
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                  ),
                  const SizedBox(height: 18),
                  const Text(
                    'Convivencia escolar',
                    style: TextStyle(
                      color: Color(0xFF202D41),
                      fontSize: 19,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 5),
                  const Text(
                    'Comunica una situación que necesite atención.',
                    style: TextStyle(color: Color(0xFF8A95A6), fontSize: 12),
                  ),
                  const SizedBox(height: 16),
                  DropdownButtonFormField<String>(
                    value: _incidentType,
                    decoration: const InputDecoration(
                      labelText: 'Tipo de situación',
                      border: OutlineInputBorder(),
                    ),
                    items: const [
                      DropdownMenuItem(value: 'Física', child: Text('Física')),
                      DropdownMenuItem(value: 'Verbal', child: Text('Verbal')),
                      DropdownMenuItem(
                        value: 'Ciberacoso',
                        child: Text('Ciberacoso'),
                      ),
                      DropdownMenuItem(
                        value: 'Exclusión',
                        child: Text('Exclusión'),
                      ),
                    ],
                    onChanged: (value) {
                      if (value != null) {
                        setState(() => _incidentType = value);
                      }
                    },
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: _descriptionController,
                    maxLines: 3,
                    maxLength: 500,
                    decoration: const InputDecoration(
                      labelText: 'Descripción',
                      hintText: 'Describe brevemente lo ocurrido',
                      border: OutlineInputBorder(),
                      alignLabelWithHint: true,
                    ),
                    validator: (value) => value == null || value.trim().isEmpty
                        ? 'Escribe una breve descripción'
                        : null,
                  ),
                  CheckboxListTile(
                    value: _anonymous,
                    contentPadding: EdgeInsets.zero,
                    controlAffinity: ListTileControlAffinity.leading,
                    title: const Text(
                      'Enviar de forma anónima',
                      style: TextStyle(fontSize: 12),
                    ),
                    onChanged: (value) {
                      setState(() => _anonymous = value ?? false);
                    },
                  ),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(11),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF7E6),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Text(
                      'Prototipo visual: este formulario no envía reportes reales.',
                      style: TextStyle(
                        color: Color(0xFF8A6A2B),
                        fontSize: 10,
                      ),
                    ),
                  ),
                  const SizedBox(height: 14),
                  SizedBox(
                    width: double.infinity,
                    child: FilledButton(
                      onPressed: () {
                        if (_formKey.currentState!.validate()) {
                          widget.onSubmit();
                        }
                      },
                      child: const Text('Continuar'),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _PlaceholderTab extends StatelessWidget {
  const _PlaceholderTab({
    required this.icon,
    required this.title,
    required this.description,
    required this.message,
  });

  final IconData icon;
  final String title;
  final String description;
  final String message;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(28),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 38, color: const Color(0xFF4778ED)),
            const SizedBox(height: 14),
            Text(
              title,
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Color(0xFF202D41),
                fontSize: 20,
                fontWeight: FontWeight.w800,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              description,
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Color(0xFF8A95A6),
                fontSize: 13,
                height: 1.5,
              ),
            ),
            const SizedBox(height: 18),
            Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: Colors.white,
                border: Border.all(color: const Color(0xFFEDF0F5)),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                message,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Color(0xFF718096),
                  fontSize: 12,
                  height: 1.5,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _MetricCard extends StatelessWidget {
  const _MetricCard({
    required this.title,
    required this.value,
    required this.detail,
    required this.icon,
    required this.color,
    required this.background,
  });

  final String title;
  final String value;
  final String detail;
  final IconData icon;
  final Color color;
  final Color background;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: (MediaQuery.sizeOf(context).width - 42) / 2,
      child: Container(
        constraints: const BoxConstraints(minHeight: 125),
        padding: const EdgeInsets.all(13),
        decoration: BoxDecoration(
          color: Colors.white,
          border: Border.all(color: const Color(0xFFEDF0F5)),
          borderRadius: BorderRadius.circular(11),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 30,
              height: 30,
              decoration: BoxDecoration(
                color: background,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, color: color, size: 17),
            ),
            const Spacer(),
            Text(
              title,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(color: Color(0xFF8994A5), fontSize: 10),
            ),
            const SizedBox(height: 4),
            Text(
              value,
              style: const TextStyle(
                color: Color(0xFF243148),
                fontSize: 21,
                fontWeight: FontWeight.w800,
              ),
            ),
            Text(
              detail,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(color: Color(0xFFA2AAB7), fontSize: 9),
            ),
          ],
        ),
      ),
    );
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle({required this.title, this.action});

  final String title;
  final String? action;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          title,
          style: const TextStyle(
            color: Color(0xFF273449),
            fontSize: 14,
            fontWeight: FontWeight.w700,
          ),
        ),
        if (action != null)
          Text(
            action!,
            style: const TextStyle(
              color: Color(0xFF4778ED),
              fontSize: 10,
              fontWeight: FontWeight.w600,
            ),
          ),
      ],
    );
  }
}

class _AlertCard extends StatelessWidget {
  const _AlertCard({
    required this.initials,
    required this.student,
    required this.detail,
    required this.severity,
    required this.color,
    required this.background,
  });

  final String initials;
  final String student;
  final String detail;
  final String severity;
  final Color color;
  final Color background;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: const Color(0xFFEDF0F5)),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Row(
        children: [
          CircleAvatar(
            radius: 17,
            backgroundColor: background,
            child: Text(
              initials,
              style: TextStyle(color: color, fontSize: 10),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  student,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: Color(0xFF3D495C),
                    fontSize: 11,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  detail,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style:
                      const TextStyle(color: Color(0xFF9AA3B1), fontSize: 9),
                ),
              ],
            ),
          ),
          const SizedBox(width: 7),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 5),
            decoration: BoxDecoration(
              color: background,
              borderRadius: BorderRadius.circular(5),
            ),
            child: Text(
              severity,
              style: TextStyle(color: color, fontSize: 8),
            ),
          ),
        ],
      ),
    );
  }
}

class _AverageCard extends StatelessWidget {
  const _AverageCard();

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(15),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: const Color(0xFFEDF0F5)),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Row(
        children: [
          const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                '15.6 / 20',
                style: TextStyle(
                  color: Color(0xFF243148),
                  fontSize: 18,
                  fontWeight: FontWeight.w800,
                ),
              ),
              SizedBox(height: 3),
              Text(
                'Promedio institucional',
                style: TextStyle(color: Color(0xFF8994A5), fontSize: 10),
              ),
            ],
          ),
          const Spacer(),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
            decoration: BoxDecoration(
              color: const Color(0xFFEAF7F1),
              borderRadius: BorderRadius.circular(6),
            ),
            child: const Text(
              '+2.3%',
              style: TextStyle(
                color: Color(0xFF288565),
                fontSize: 9,
                fontWeight: FontWeight.w700,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _QuickAction extends StatelessWidget {
  const _QuickAction({
    required this.icon,
    required this.title,
    required this.color,
    this.onTap,
  });

  final IconData icon;
  final String title;
  final Color color;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(10),
        child: Container(
          constraints: const BoxConstraints(minHeight: 74),
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: Colors.white,
            border: Border.all(color: const Color(0xFFEDF0F5)),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, color: color, size: 18),
              const SizedBox(height: 8),
              Text(
                title,
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(
                  color: Color(0xFF56657A),
                  fontSize: 9,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _BrandMark extends StatelessWidget {
  const _BrandMark();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 36,
      height: 36,
      alignment: Alignment.center,
      decoration: BoxDecoration(
        color: const Color(0xFF1D3D73),
        borderRadius: BorderRadius.circular(11),
      ),
      child: const Text(
        'SL',
        style: TextStyle(
          color: Colors.white,
          fontSize: 12,
          fontWeight: FontWeight.w800,
        ),
      ),
    );
  }
}

class _NavItem {
  const _NavItem(this.label, this.icon);

  final String label;
  final IconData icon;
}
